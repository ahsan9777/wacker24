<?php
include("../lib/openCon.php");
include("../lib/functions.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {

        case 'cat_title':
            $json = array();
            $where = "";
            $sub_cat_title = 0;
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                if (isset($_REQUEST['parent_id']) ) {
                    $sub_cat_title = 1;
                    if($_REQUEST['parent_id'] == 0){
                        $where .= " WHERE cat.parent_id > '0' AND ( cat.cat_title_de LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR cat.cat_title_en LIKE '%" . dbStr($_REQUEST['term']) . "%')";
                    } else{
                        $where .= " WHERE cat.parent_id IN ( SELECT main_cat.group_id FROM category AS main_cat WHERE main_cat.parent_id = '".$_REQUEST['parent_id']."' ORDER BY main_cat.group_id ASC) AND ( cat.cat_title_de LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR cat.cat_title_en LIKE '%" . dbStr($_REQUEST['term']) . "%')";
                    }
                    $Query = "SELECT cat.cat_id, cat.cat_title_de AS cat_title, sub_cat.cat_title_de AS sub_cat_title FROM `category` AS cat LEFT OUTER JOIN category AS sub_cat ON sub_cat.group_id = cat.parent_id  " . $where . " ORDER BY cat.cat_id  LIMIT 0,20";
                } else {
                    $where .= " WHERE cat.parent_id = '0' AND ( cat.cat_title_de LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR cat.cat_title_en LIKE '%" . dbStr($_REQUEST['term']) . "%')";
                    $Query = "SELECT cat.cat_id, cat.cat_title_de AS cat_title FROM `category` AS cat " . $where . " ORDER BY cat.cat_id  LIMIT 0,20";
                }
            }
            //print($Query);
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'cat_id' => strip_tags(html_entity_decode($row->cat_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->cat_title. (($sub_cat_title > 0)? '( '.$row->sub_cat_title. ')' : ''), ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'btn_toggle':
            $retValue = array();
            //print_r($_REQUEST);die();
            $data_update = mysqli_query($GLOBALS['conn'], "UPDATE " . $_REQUEST['table'] . " SET " . $_REQUEST['set_field'] . " = '" . $_REQUEST['set_field_data'] . "' WHERE " . $_REQUEST['where_field'] . " = " . $_REQUEST['id']) or die(mysqli_error($GLOBALS['conn']));
            if ($data_update == true) {
                $retValue = array("status" => "1", "message" => "Record Updated successfully");
            } else {
                $retValue = array("status" => "0", "message" => "Record not Updated");
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'pro_description_short':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $pro_custom_add = "";
                if(isset($_REQUEST['pro_custom_add']) && $_REQUEST['pro_custom_add'] > 0){
                    $pro_custom_add = " AND pro_custom_add = '1'";
                }
                $where .= " WHERE pro_description_short LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ".$pro_custom_add." ";
            }
            $Query = "SELECT pro_id, pro_description_short FROM products " . $where . " ORDER BY pro_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'pro_id' => strip_tags(html_entity_decode($row->pro_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->pro_description_short, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'pro_update_quantity':
            $retValue = array();
            //print_r($_REQUEST);die();
            $data_update = mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET pq_quantity = '" . dbStr(trim($_REQUEST['pq_quantity'])) . "', pq_upcomming_quantity = '" . dbStr(trim($_REQUEST['pq_upcomming_quantity'])) . "' WHERE pq_id = '" . dbStr(trim($_REQUEST['pq_id'])) . "' AND supplier_id = '" . dbStr(trim($_REQUEST['supplier_id'])) . "' ") or die(mysqli_error($GLOBALS['conn']));
            if ($data_update == true) {
                $retValue = array("status" => "1", "message" => "Record Updated successfully");
                $retValue['data'][] = array(
                    "pq_quantity" => strval($_REQUEST['pq_quantity']),
                    "pq_upcomming_quantity" => strval($_REQUEST['pq_upcomming_quantity'])
                );
            } else {
                $retValue = array("status" => "0", "message" => "Record not Updated");
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'pro_update_price':
            //print_r($_REQUEST);
            $retValue = array();
            //print($_REQUEST['priceData'][0]['pbp_price_amount']);die();
            $count = 0;
            for ($i = 0; $i < count($_REQUEST['priceData']); $i++) {
                $data_update = mysqli_query($GLOBALS['conn'], "UPDATE products_bundle_price SET pbp_price_amount = '" . dbStr(trim($_REQUEST['priceData'][$i]['pbp_price_amount'])) . "' WHERE pbp_id = '" . dbStr(trim($_REQUEST['priceData'][$i]['pbp_id'])) . "' AND pro_id = '" . dbStr(trim($_REQUEST['pro_id'])) . "' AND supplier_id = '" . dbStr(trim($_REQUEST['supplier_id'])) . "' ") or die(mysqli_error($GLOBALS['conn']));
                $count++;
            }
            if ($data_update == true) {
                $retValue = array("status" => "1", "message" => "Record Updated successfully");
            } else {
                $retValue = array("status" => "0", "message" => "Record not Updated");
            }

            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'user_full_name':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE u.utype_id IN (3,4) AND (u.user_fname LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR u.user_lname LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR ut.utype_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR u.user_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%')";
            }
            $Query = "SELECT u.user_id, CONCAT(u.user_fname, ' ', u.user_lname, ' ( ',ut.utype_name,' )') full_name FROM users AS u LEFT OUTER JOIN user_type AS ut ON ut.utype_id = u.utype_id " . $where . " ORDER BY u.user_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'user_id' => strip_tags(html_entity_decode($row->user_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->full_name, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'brand_name':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE brand_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ";
            }
            $Query = "SELECT brand_id, brand_name FROM brands " . $where . " ORDER BY brand_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'brand_id' => strip_tags(html_entity_decode($row->brand_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->brand_name, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;
        
        case 'manf_name':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE manf_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ";
            }
            $Query = "SELECT manf_id, manf_name FROM manufacture " . $where . " ORDER BY manf_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'manf_id' => strip_tags(html_entity_decode($row->manf_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->manf_name, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'ord_id':
            $json = array();
            $where = "WHERE 1 = 1";
            if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
                $where .= " AND user_id = '" . dbStr(trim($_REQUEST['user_id'])) . "'";
            }
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " AND ord_id LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%'";
            }
            $Query = "SELECT ord_id FROM orders " . $where . " ORDER BY ord_id  LIMIT 0,20";
            //print($Query);
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'value' => strip_tags(html_entity_decode($row->ord_id, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'order_user_title':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE ut.utype_id IN (3,4) AND (u.user_fname LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR u.user_lname LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR ut.utype_name LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%')";
            }
            $Query = "SELECT DISTINCT(u.user_id), CONCAT(u.user_fname, ' ', u.user_lname, ' ( ',ut.utype_name,' )') full_name FROM users AS u LEFT OUTER JOIN user_type AS ut ON ut.utype_id = u.utype_id INNER JOIN orders AS ord ON ord.user_id = u.user_id " . $where . " ORDER BY u.user_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'order_user_id' => strip_tags(html_entity_decode($row->user_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->full_name, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'level_one':
            //print_r($_REQUEST);die();
            $retValue = array();
            $level_one_data = "";
            $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title FROM category AS cat WHERE cat.parent_id = '" . dbStr(trim($_REQUEST['level_one_id'])) . "' ORDER BY cat.cat_id ASC";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                $level_one_data .= "<option value='0'>N/A</option>";
                while ($row = mysqli_fetch_object($rs)) {
                    $level_one_data .= "<option value=" . $row->group_id . "  > " . $row->cat_title . " </option>";
                }

                $retValue = array("status" => "1", "message" => "Record found successfully!",  "level_one_data" => $level_one_data);
            } else {
                $level_one_data = "<option value=''>No record found!</option>";
                $retValue = array("status" => "0", "message" => "Record not found!", "level_one_data" => $level_one_data);
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'level_two':
            //print_r($_REQUEST);die();
            $retValue = array();
            $level_two_data = "";
            $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title FROM category AS cat WHERE cat.parent_id = '" . dbStr(trim($_REQUEST['level_two_id'])) . "' ORDER BY cat.cat_id ASC";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $level_two_data .= "<option value=" . $row->group_id . "  > " . $row->cat_title . " </option>";
                }

                $retValue = array("status" => "1", "message" => "Record found successfully!",  "level_two_data" => $level_two_data);
            } else {
                $level_two_data = "<option value=''>No record found!</option>";
                $retValue = array("status" => "0", "message" => "Record not found!", "level_two_data" => $level_two_data);
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'cat_min_pbp_price_amount':
            //print_r($_REQUEST);die();
            $retValue = array();
            //$Query = "SELECT cm.*, pbp.*, MIN(pbp.pbp_price_amount) AS cat_min_pbp_price_amount FROM category_map AS cm LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' WHERE FIND_IN_SET('" . (($_REQUEST['level_two_id'] > 0) ? dbStr($_REQUEST['level_two_id']) : dbStr($_REQUEST['level_one_id'])) . "', cm.sub_group_ids)";
            $Query = "SELECT  MIN(pbp.pbp_price_amount) AS cat_min_pbp_price_amount FROM category_map AS cm LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' WHERE FIND_IN_SET('" . (($_REQUEST['level_two_id'] > 0) ? dbStr($_REQUEST['level_two_id']) : dbStr($_REQUEST['level_one_id'])) . "', cm.sub_group_ids)";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                $retValue = array("status" => "1", "message" => "Record Updated successfully");
                $row = mysqli_fetch_object($rs);
                $retValue['data'][] = array(
                    "pbp_price_amount" => strval($row->cat_min_pbp_price_amount)
                );
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!");
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'special_price_pro_title':
            //print_r($_REQUEST);die();
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                $where .= " WHERE pro.pro_status = '1' AND FIND_IN_SET('" . (($_REQUEST['level_two_id'] > 0) ? dbStr(trim($_REQUEST['level_two_id'])) : dbStr(trim($_REQUEST['level_one_id']))) . "', cm.sub_group_ids) AND ( pro.pro_description_short LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR pro.supplier_id LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%') ";
            }
            $Query = "SELECT cm.cat_id, cm.supplier_id, pro.pro_id, pro.pro_description_short, pro.pro_status FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id " . $where . " ORDER BY pro.pro_id ASC  LIMIT 0,20";
            //print($Query);die(); 120026930
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                    'supplier_id' => strip_tags(html_entity_decode($row->supplier_id, ENT_QUOTES, 'UTF-8')),
                    'value' => strip_tags(html_entity_decode($row->pro_description_short, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;

        case 'special_price_product_data':

            $retValue = array();
            $special_price_product_data = "";
            $Query = "SELECT pro.*, pbp.pbp_price_amount, pg.pg_mime_source_url FROM products AS pro LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE pro.supplier_id = '" . $_REQUEST['supplier_id'] . "' ORDER BY pro.pro_id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {

                    $special_price_product_data .= '
                                        <div class="col-md-2 col-12 mt-3">
                                                <div class="popup_container" style = "width: 110px;">
                                                    <div class="container__img-holder">
                                                        <img src="' . $row->pg_mime_source_url . '">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 mt-3">
                                                <div class="d-flex gap-2 mt-3">
                                                    <div class="d-flex gap-2">
                                                        <label for="">Percentage: </label>
                                                        <input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_type" value="0" checked>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <label for="">Fix: </label>
                                                        <input type="radio" class="usp_price_type" name="usp_price_type" id="usp_price_type" value="1">
                                                    </div>
                                                </div>
                                                <label for="">Value</label>
                                                <input type="number" step="any" class="input_style usp_discounted_value" name="usp_discounted_value" id="usp_discounted_value" value="0" required placeholder="Value">
                                            </div>
                                            <div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">
                                                <label for="">Price</label>
                                                <input type="number" readonly class="input_style" name="pbp_price_amount" id="pbp_price_amount" value="' . $row->pbp_price_amount . '" placeholder="Price">
                                            </div>
                                            <div class="col-md-3 col-12 mt-3 d-flex flex-column justify-content-end">
                                                <label for="">Discounted Price</label>
                                                <input type="number" readonly class="input_style" name="usp_discounted_price" id="usp_discounted_price" value="0" placeholder="Discounted Price">
                                            </div>
                                            <script>
                                            $(document).ready(function() {

                                                        // required elements
                                                        var imgPopup = $(".img-popup");
                                                        var imgCont = $(".container__img-holder");
                                                        var popupImage = $(".img-popup img");
                                                        var closeBtn = $(".close-btn");

                                                        // handle events
                                                        imgCont.on("click", function() {
                                                            var img_src = $(this).children("img").attr("src");
                                                            imgPopup.children("img").attr("src", img_src);
                                                            imgPopup.addClass("opened");
                                                        });

                                                        $(imgPopup, closeBtn).on("click", function() {
                                                            imgPopup.removeClass("opened");
                                                            imgPopup.children("img").attr("src", "");
                                                        });

                                                        popupImage.on("click", function(e) {
                                                            e.stopPropagation();
                                                        });

                                                    });

                                                    $(".usp_price_type").on("click", function() {
                                                        //console.log("usp_price_type");
                                                        $(".usp_discounted_value").trigger("keyup");
                                                    });
                                                    $(".usp_discounted_value").on("keyup", function() {

                                                        let usp_price_type = $("input[name=\'usp_price_type\']:checked").val();
                                                        let usp_discounted_value = $("#usp_discounted_value").val();
                                                        let pbp_price_amount = $("#pbp_price_amount").val();
                                                        let usp_discounted_price = 0;
                                                        let percentage = 0;
                                                        console.log("usp_discounted_value: " + usp_discounted_value + " usp_price_type: " + usp_price_type + " pbp_price_amount: " + pbp_price_amount);
                                                        if (usp_discounted_value > 0) {
                                                            if (usp_price_type == 1) {
                                                                if (parseFloat(usp_discounted_value) <= parseFloat(pbp_price_amount)) {
                                                                    usp_discounted_price = (pbp_price_amount - usp_discounted_value).toFixed(2);
                                                                } else {
                                                                    $("#usp_discounted_value").val(0);
                                                                    usp_discounted_price = 0;
                                                                }
                                                            } else {
                                                                percentage = (pbp_price_amount * usp_discounted_value) / 100;
                                                                usp_discounted_price = (pbp_price_amount - percentage).toFixed(2);
                                                            }
                                                        }
                                                        $("#usp_discounted_price").val(usp_discounted_price);
                                                    });
                                            </script>
                                        
                                ';
                }

                $retValue = array("status" => "1", "message" => "Record found successfully!",  "special_price_product_data" => $special_price_product_data);
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!");
            }
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'get_product_list':
            //print_r($_REQUEST);die();
            $Query = "SELECT pro.pro_id, pro.supplier_id, pro.pro_description_short FROM products AS pro WHERE pro.pro_status > 0 ORDER BY pro.pro_id ASC";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    echo "<option value='$row->supplier_id'>$row->pro_description_short</option>";
                }
            } else {
                echo "<option value=''>No record found!</option>";
            }
            break;
    }
}
