<?php
ob_start();

include("lib/openCon.php");
include("lib/functions.php");
if (!isset($_REQUEST['search_keyword'])) {
    require_once("lib/class.pager3.php");
    $p = new Pager1;
}
require_once("lib/mailer.php");
$mailer = new Mailer();

session_start();
//$_SESSION['utype_id'] = 3;

$page = 0;

$class = "";
$strMSG = "";
$qryStrURL = "";
$search_keyword = "";
$cat_id = "";
$special_price = array();
$page_title = "";
$page_bottom_js = 1;
$meta_keywords = "";
$meta_description = "";

//if ((isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) && (isset($_SESSION["cart_id"]) && $_SESSION["cart_id"] > 0) && (isset($_SESSION["utype_id"]) && in_array($_SESSION["utype_id"], array(3,4)))) {
if ((isset($_SESSION["cart_id"]) && $_SESSION["cart_id"] > 0) && (isset($_SESSION["cart_check"]) && $_SESSION["cart_check"] == true)) {
    $cart_id = $_SESSION['cart_id'];
    mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_discounted_price_see = '0' WHERE cart_id = '" . $cart_id . "'") or die(mysqli_error($GLOBALS['conn']));
    $Query = "SELECT ci.*, cm.sub_group_ids FROM cart_items AS ci LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = ci.supplier_id WHERE ci_type IN (0,1) cart_id = '" . $cart_id . "' AND ci_discounted_price_see = '0' ";
    //print($Query);die();
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_object($rs)) {

            $pro_id = $row->pro_id;
            $supplier_id = $row->supplier_id;
            $ci_qty = $row->ci_qty;
            $sub_group_ids = explode(",", $row->sub_group_ids);
            //print_r($sub_group_ids);
            $cat_id_one = $sub_group_ids[1];
            $cat_id_two = $sub_group_ids[0];
            $special_price = user_special_price("supplier_id", $supplier_id);
            if (!$special_price) {
                $special_price = user_special_price("level_two", $cat_id_two);
            }
            if (!$special_price) {
                $special_price = user_special_price("level_one", $cat_id_one);
            }
            //print_r($special_price);die();
            $get_pro_price = get_pro_price($pro_id, $supplier_id, $ci_qty);
            //print_r($get_pro_price);//die();
            //$pbp_price_amount = $row->pbp_price_amount;
            //$ci_amount = $row->ci_amount;
            $pbp_price_amount = $get_pro_price['ci_amount'];
            $ci_amount = $get_pro_price['ci_amount'];
            $ci_gst_value = $get_pro_price['ci_gst_value'];
            $ci_discount_type = !empty($special_price) ? $special_price['usp_price_type'] : 0;
            $ci_discount_value = !empty($special_price) ? $special_price['usp_discounted_value'] : 0;
            //print($ci_discount_value);die();
            $ci_discounted_amount = 0;
            $ci_discount = 0;
            if ($ci_discount_value > 0) {
                $ci_discounted_amount_gross = 0;
                $ci_amount = discounted_price($ci_discount_type, $ci_amount, $ci_discount_value);
                $ci_discounted_amount = $pbp_price_amount - $ci_amount;

                $ci_discounted_amount_gross = $ci_discounted_amount * $ci_qty;
                $ci_discount = $ci_discounted_amount_gross + ($ci_discounted_amount_gross * $ci_gst_value);
            }
            $ci_gross_total = $ci_amount * $ci_qty;
            $ci_gst = $ci_gross_total * $ci_gst_value;
            $ci_total = $ci_gross_total + $ci_gst;
            //die();
            $updated_cart_item = false;
            $update_cart = false;
            $updated_cart_item = mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET  ci_amount = '" . $ci_amount . "', ci_discounted_amount = '" . $ci_discounted_amount . "',  ci_gross_total = '$ci_gross_total' , ci_gst_value = '" . $ci_gst_value . "', ci_gst = '$ci_gst', ci_discount_type = '$ci_discount_type', ci_discount_value = '$ci_discount_value',  ci_discount =  '$ci_discount', ci_total =  '$ci_total' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
            $update_cart = mysqli_query($GLOBALS['conn'], "UPDATE cart SET cart_gross_total=(SELECT SUM(ci_gross_total) FROM cart_items WHERE cart_id=$cart_id), cart_gst=(SELECT SUM(ci_gst) FROM cart_items WHERE cart_id=$cart_id), cart_discount=(SELECT SUM(ci_discount) FROM cart_items WHERE cart_id=$cart_id), cart_amount=(SELECT SUM(ci_total) FROM cart_items WHERE cart_id=$cart_id) WHERE cart_id=" . $cart_id) or die(mysqli_error($GLOBALS['conn']));
            if ($updated_cart_item == true && $update_cart == true) {
                mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET  ci_discounted_price_see = '1' WHERE ci_id = '" . $row->ci_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            $special_price = array();
        }
    }
    $_SESSION["cart_check"] = false;
}
if (isset($_REQUEST['btn_plz'])) {
    $_SESSION['plz'] = $_REQUEST['plz'];
    getShippingTiming($_SESSION['plz']);
}
