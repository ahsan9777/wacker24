<?php
include("../lib/session_head.php");


if (isset($_REQUEST['pro_status']) && $_REQUEST['pro_status'] > 0) {
    $pro_status = $_REQUEST['pro_status'];
    $qryStrURL .= "pro_status=" . $pro_status . "&";
}
if (isset($_REQUEST['btnImport']) || isset($_REQUEST['btnImportSchulranzen'])) {
    //print_r($_REQUEST);die();
    //$xml = simplexml_load_file("BMEcat2005_119053.xml") or die("Error: Cannot create object");
    $pro_type = 0;
    if (isset($_REQUEST['btnImportSchulranzen'])) {
        $pro_type = 20;
        $xml = simplexml_load_file("schulranzen.xml") or die("Error: Cannot create object");
        mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status = '0' WHERE pro_type = '" . $pro_type . "'") or die(mysqli_error($GLOBALS['conn']));
    } else {
        //$xml = simplexml_load_file("BMEcat2005_119053.xml") or die("Error: Cannot create object");
        $xml = simplexml_load_file("artical.xml") or die("Error: Cannot create object");
        mysqli_query($GLOBALS['conn'], "UPDATE products SET  pro_status = '0' WHERE pro_type = '" . $pro_type . "'") or die(mysqli_error($GLOBALS['conn']));
    }
    /*print('<pre>');
    print_r($xml->T_NEW_CATALOG->PRODUCT);
    //print_r($xml->T_NEW_CATALOG->PRODUCT->USER_DEFINED_EXTENSIONS->{'UDX.SOE.EPAG_ID'});
    //print_r($xml->T_NEW_CATALOG->PRODUCT->USER_DEFINED_EXTENSIONS->{'UDX.SOE.SELECTIONFEATURE'});
    //print_r($xml->T_NEW_CATALOG->ARTICLE->PRODUCT_DETAILS->KEYWORD);
    //print_r($xml->T_NEW_CATALOG->PRODUCT->PRODUCT_FEATURES[1]->REFERENCE_FEATURE_GROUP_ID);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->PRODUCT_ORDER_DETAILS);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->PRODUCT_PRICE_DETAILS->PRODUCT_PRICE[1]);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->MIME_INFO->MIME[0]);
    print('</pre>');die();*/
    foreach ($xml->T_NEW_CATALOG->PRODUCT as $rl) {
        //echo $i++." ".$rl->ART_ID.PHP_EOL;
        $pro_uid = 0;
        $pro_id = getMaximum("products", "pro_id");
        $supplier_id = isset($rl->SUPPLIER_PID) ? $rl->SUPPLIER_PID : '';
        $pro_description_short = isset($rl->PRODUCT_DETAILS->DESCRIPTION_SHORT) ? $rl->PRODUCT_DETAILS->DESCRIPTION_SHORT : '';
        $pro_description_long = isset($rl->PRODUCT_DETAILS->DESCRIPTION_LONG) ? $rl->PRODUCT_DETAILS->DESCRIPTION_LONG : '';
        $pro_ean = isset($rl->PRODUCT_DETAILS->INTERNATIONAL_PID) ? $rl->PRODUCT_DETAILS->INTERNATIONAL_PID : '';
        $pro_buyer_id = isset($rl->PRODUCT_DETAILS->BUYER_PID) ? $rl->PRODUCT_DETAILS->BUYER_PID : '';
        $pro_manufacture_aid = isset($rl->PRODUCT_DETAILS->MANUFACTURER_PID) ? $rl->PRODUCT_DETAILS->MANUFACTURER_PID : '';
        if($pro_type == 20){
            $pro_manufacture_name = isset($rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.MARKE'}) ? $rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.MARKE'} : '';
        } else {
            $pro_manufacture_name = isset($rl->PRODUCT_DETAILS->MANUFACTURER_NAME) ? $rl->PRODUCT_DETAILS->MANUFACTURER_NAME : '';
        }
        $pro_keyword = isset($rl->PRODUCT_DETAILS->KEYWORD) ? $rl->PRODUCT_DETAILS->KEYWORD : ''; //product_keyword
        $pro_referance_feature_group_id = isset($rl->PRODUCT_FEATURES[0]->REFERENCE_FEATURE_GROUP_ID) ? $rl->PRODUCT_FEATURES[0]->REFERENCE_FEATURE_GROUP_ID : '';
        $pro_feature = isset($rl->PRODUCT_FEATURES[1]->FEATURE) ? $rl->PRODUCT_FEATURES[1]->FEATURE : ''; // product_feature
        $referance_feature_group_id = isset($rl->PRODUCT_FEATURES[2]->REFERENCE_FEATURE_GROUP_ID) ? $rl->PRODUCT_FEATURES[2]->REFERENCE_FEATURE_GROUP_ID : '';

        $pro_order_unit = isset($rl->PRODUCT_ORDER_DETAILS->ORDER_UNIT) ? $rl->PRODUCT_ORDER_DETAILS->ORDER_UNIT : '';
        $pro_count_unit = isset($rl->PRODUCT_ORDER_DETAILS->CONTENT_UNIT) ? $rl->PRODUCT_ORDER_DETAILS->CONTENT_UNIT : '';
        $pro_no_cu_per_ou = isset($rl->PRODUCT_ORDER_DETAILS->NO_CU_PER_OU) ? $rl->PRODUCT_ORDER_DETAILS->NO_CU_PER_OU : '';
        $pro_price_quantity = isset($rl->PRODUCT_ORDER_DETAILS->PRICE_QUANTITY) ? $rl->PRODUCT_ORDER_DETAILS->PRICE_QUANTITY : '';
        $pro_quantity_min = isset($rl->PRODUCT_ORDER_DETAILS->QUANTITY_MIN) ? $rl->PRODUCT_ORDER_DETAILS->QUANTITY_MIN : 0;
        $pro_quantity_interval = isset($rl->PRODUCT_ORDER_DETAILS->QUANTITY_INTERVAL) ? $rl->PRODUCT_ORDER_DETAILS->QUANTITY_INTERVAL : 0;
        $pro_artical_price = isset($rl->PRODUCT_PRICE_DETAILS->PRODUCT_PRICE) ? $rl->PRODUCT_PRICE_DETAILS->PRODUCT_PRICE : ''; //product_bundle_price
        $pro_gallery = isset($rl->MIME_INFO->MIME) ? $rl->MIME_INFO->MIME : ''; //products_gallery
        $pro_udx_seo_internetbezeichung = isset($rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.INTERNETBEZEICHNUNG'}) ? $rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.INTERNETBEZEICHNUNG'} : '';
        $pro_udx_seo_epag_id = isset($rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.EPAG_ID'}) ? $rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.EPAG_ID'} : '';
        $pro_udx_seo_selection_feature = isset($rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.SELECTIONFEATURE'}) ? $rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.SELECTIONFEATURE'} : '';
        $pro_udx_seo_pk = isset($rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.PK'}) ? $rl->USER_DEFINED_EXTENSIONS->{'UDX.SOE.PK'} : '';
        $pro_delivery_time = isset($rl->PRODUCT_LOGISTIC_DETAILS->DELIVERY_TIMES->TIME_SPAN->TIME_VALUE_DURATION) ? $rl->PRODUCT_LOGISTIC_DETAILS->DELIVERY_TIMES->TIME_SPAN->TIME_VALUE_DURATION : 0;
        //print($pg_mime_source = basename($pro_gallery[0]->MIME_SOURCE));die();
        //print($pro_udx_seo_pk);die();
        /*print('<pre>');
            print_r($pro_artical_price); 
            print('</pre>');die();*/

        $Query1 = "SELECT * FROM manufacture WHERE  manf_name = '" . dbStr(trim($pro_manufacture_name)) . "'";
        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
        if (mysqli_num_rows($rs1) > 0) {
            $row1 = mysqli_fetch_object($rs1);
            $manf_id = $row1->manf_id;
        } else {
            $manf_id = getMaximum("manufacture", "manf_id");
            mysqli_query($GLOBALS['conn'], "INSERT INTO manufacture (manf_id, manf_name, manf_name_params) VALUES ('" . $manf_id . "', '" . dbStr(trim($pro_manufacture_name)) . "', '" . dbStr(trim(url_clean($pro_manufacture_name))) . "')") or die(mysqli_error($GLOBALS['conn']));
        }

        $Query2 = "SELECT * FROM products WHERE  supplier_id = '" . $supplier_id . "'";
        $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
        if (mysqli_num_rows($rs2) > 0) {
            $row2 = mysqli_fetch_object($rs2);
            $pro_uid = $row2->pro_id;
            $pro_id = $row2->pro_id;
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status = '1', pro_description_short = '" . dbStr(trim($pro_description_short)) . "', pro_description_long = '" . dbStr(trim($pro_description_long)) . "', pro_ean = '" . dbStr(trim($pro_ean)) . "', pro_buyer_id = '" . dbStr(trim($pro_buyer_id)) . "', manf_id = '" . dbStr(trim($manf_id)) . "', pro_delivery_time = '" . dbStr(trim($pro_delivery_time)) . "', pro_order_unit = '" . dbStr(trim($pro_order_unit)) . "', pro_count_unit = '" . dbStr(trim($pro_count_unit)) . "', pro_no_cu_per_ou = '" . dbStr(trim($pro_no_cu_per_ou)) . "', pro_price_quantity = '" . dbStr(trim($pro_price_quantity)) . "', pro_quantity_min = '" . dbStr(trim($pro_quantity_min)) . "', pro_quantity_interval = '" . dbStr(trim($pro_quantity_interval)) . "', pro_udx_seo_internetbezeichung = '" . dbStr(trim($pro_udx_seo_internetbezeichung)) . "', pro_udx_seo_epag_id = '" . dbStr(trim($pro_udx_seo_epag_id)) . "', pro_udx_seo_selection_feature = '" . dbStr(trim($pro_udx_seo_selection_feature)) . "', pro_updatedby = '" . $_SESSION["UserID"] . "', pro_udate = '" . date_time . "'  WHERE pro_id = '" . $pro_uid . "' ") or die(mysqli_error($GLOBALS['conn']));
        } else {

            mysqli_query($GLOBALS['conn'], "INSERT INTO products (pro_id, pro_type, supplier_id, pro_description_short, pro_description_long, pro_ean, pro_buyer_id, manf_id, pro_manufacture_aid, pro_delivery_time, pro_order_unit, pro_count_unit, pro_no_cu_per_ou, pro_price_quantity, pro_quantity_min, pro_quantity_interval, pro_udx_seo_internetbezeichung, pro_udx_seo_epag_id, pro_udx_seo_selection_feature, pro_addedby, pro_cdate) VALUES ('" . $pro_id . "', '" . $pro_type . "', '" . $supplier_id . "', '" . dbStr(trim($pro_description_short)) . "', '" . dbStr(trim($pro_description_long)) . "', '" . dbStr(trim($pro_ean)) . "', '" . dbStr(trim($pro_buyer_id)) . "', '" . dbStr(trim($manf_id)) . "', '" . dbStr(trim($pro_manufacture_aid)) . "', '" . dbStr(trim($pro_delivery_time)) . "', '" . dbStr(trim($pro_order_unit)) . "', '" . dbStr(trim($pro_count_unit)) . "', '" . dbStr(trim($pro_no_cu_per_ou)) . "', '" . dbStr(trim($pro_price_quantity)) . "', '" . dbStr(trim($pro_quantity_min)) . "', '" . dbStr(trim($pro_quantity_interval)) . "', '" . dbStr(trim($pro_udx_seo_internetbezeichung)) . "', '" . dbStr(trim($pro_udx_seo_epag_id)) . "', '" . dbStr(trim($pro_udx_seo_selection_feature)) . "', '" . $_SESSION["UserID"] . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
        }

        if ($pro_type == 20) {
            $Query3 = "SELECT * FROM category WHERE  cat_title_de = '" . $pro_udx_seo_pk . "'";
            $rs3 = mysqli_query($GLOBALS['conn'], $Query3);
            if (mysqli_num_rows($rs3) > 0) {
                $row3 = mysqli_fetch_object($rs3);
                $group_id = $row3->group_id;
            } else {
                $group_id = getMaximumWhere("category", "group_id", "WHERE parent_id = '20'");
                $cat_id = getMaximum("category", "cat_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO category (cat_id, group_id, parent_id, cat_title_de, cat_params_de) VALUES ('" . $cat_id . "', '" . $group_id . "', '20', '" . dbStr(trim($pro_udx_seo_pk)) . "', '" . dbStr(url_clean(trim($pro_udx_seo_pk))) . "')") or die(mysqli_error($GLOBALS['conn']));
            }
        }

        if (!empty($pro_keyword)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM products_keyword WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_keyword); $i++) {
                $pk_title = $pro_keyword[$i];
                $pk_id = getMaximum("products_keyword", "pk_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO products_keyword (pk_id, pro_id, supplier_id, pk_title) VALUES ('" . $pk_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($pk_title)) . "') ") or die(mysqli_error($GLOBALS['conn']));
            }
        }

        if (!empty($pro_feature)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM `products_feature` WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_feature); $i++) {
                if ($i > 0) {
                    $pf_fname = $pro_feature[$i]->FNAME;
                    $pf_fname_params_de = dbStr(trim(url_clean($pro_feature[$i]->FNAME)));
                    $pf_fvalue = $pro_feature[$i]->FVALUE;
                    $pf_fvalue_params_de = dbStr(trim(url_clean($pro_feature[$i]->FVALUE)));
                    $pf_forder = $pro_feature[$i]->FORDER;
                    $pf_fvalue_details = isset($pro_feature[$i]->FVALUE_DETAILS) ? $pro_feature[$i]->FVALUE_DETAILS : '';
                    //print($i.": pf_fname = ".$pf_fname." pf_fvalue = ".$pf_fvalue." pf_forder = ".$pf_forder." pf_fvalue_details = ".$pf_fvalue_details."<br>");
                    $pf_id = getMaximum("products_feature", "pf_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO products_feature (pf_id, pro_id, supplier_id, pro_udx_seo_epag_id, pf_group_id, pf_fname, pf_fvalue, pf_forder, pf_fvalue_details, pf_fname_params_de, pf_fvalue_params_de) VALUES ('" . $pf_id . "', '" . $pro_id . "', '" . $supplier_id . "',  '" . dbStr(trim($pro_udx_seo_epag_id)) . "', '" . $pro_referance_feature_group_id . "', '" . dbStr(trim($pf_fname)) . "', '" . dbStr(trim($pf_fvalue)) . "', '" . dbStr(trim($pf_forder)) . "', '" . dbStr(trim($pf_fvalue_details)) . "', '" . $pf_fname_params_de . "', '" . $pf_fvalue_params_de . "') ") or die(mysqli_error($GLOBALS['conn']));
                }
            }
        }

        if ($pro_type == 20) {
            if (!empty($supplier_id)) {
                if ($pro_uid > 0) {
                    mysqli_query($GLOBALS['conn'], "DELETE FROM `category_map` WHERE supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
                }
                $art_id = $supplier_id;
                $catalog_group_id = 0;
                $sub_group_ids = $group_id. ",";
                $cat_id_level_two = $group_id;
                $sub_group_ids .= 20;
                $cat_id_level_one = 20;
                //print("art_id = ".$art_id."<br>catalog_group_id = ".$catalog_group_id."<br>sub_group_ids: ".$sub_group_ids."<br>");die();

                mysqli_query($GLOBALS['conn'], "INSERT INTO category_map (cat_id, supplier_id, sub_group_ids, cm_type, cat_id_level_two, cat_id_level_one) VALUES ('" . $catalog_group_id . "', '" . $art_id . "', '" . dbStr(rtrim($sub_group_ids, ",")) . "', '" . $pro_type . "', '" . $cat_id_level_two . "', '" . $cat_id_level_one . "')") or die(mysqli_error($GLOBALS['conn']));
            }
        } else {
            if (!empty($referance_feature_group_id)) {
                if ($pro_uid > 0) {
                    mysqli_query($GLOBALS['conn'], "DELETE FROM `category_map` WHERE cat_id = '" . $referance_feature_group_id . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
                }
                
                $art_id = $supplier_id;
                $catalog_group_id = $referance_feature_group_id;
                $sub_group_ids = substr($catalog_group_id, 0, 3) . ",";
                $cat_id_level_two = substr($catalog_group_id, 0, 3);
                $sub_group_ids .= returnName("parent_id", "category", "group_id", rtrim($sub_group_ids, ","));
                $cat_id_level_one = returnName("parent_id", "category", "group_id", $cat_id_level_two);
                //print("art_id = ".$art_id."<br>catalog_group_id = ".$catalog_group_id."<br>sub_group_ids: ".$sub_group_ids."<br>");die();
                //print("supplier_id=".$supplier_id."<br>catalog_group_id=".$catalog_group_id."<br>cat_id_level_one = ".$cat_id_level_one."<br>cat_id_level_two = ".$cat_id_level_two."<br>");//die();

                mysqli_query($GLOBALS['conn'], "INSERT INTO category_map (cat_id, supplier_id, sub_group_ids, cm_type, cat_id_level_two, cat_id_level_one) VALUES ('" . $catalog_group_id . "', '" . $art_id . "', '" . dbStr(rtrim($sub_group_ids, ",")) . "', '" . $pro_type . "', '" . $cat_id_level_two . "', '" . $cat_id_level_one . "')") or die(mysqli_error($GLOBALS['conn']));
            }
        }

        if (!empty($pro_artical_price)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM `products_bundle_price` WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_artical_price); $i++) {
                $pbp_price_amount = $pro_artical_price[$i]->PRICE_AMOUNT;
                $pbp_currency = $pro_artical_price[$i]->PRICE_CURRENCY;
                $pbp_tax = $pro_artical_price[$i]->TAX;
                $pbp_lower_bound = $pro_artical_price[$i]->LOWER_BOUND;
                //print($i.": pbp_price_amount = ".$pbp_price_amount." pbp_currency = ".$pbp_currency." pbp_tax = ".$pbp_tax." pbp_lower_bound = ".$pbp_lower_bound."<br>");
                $pbp_id = getMaximum("products_bundle_price", "pbp_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO products_bundle_price (pbp_id, pro_id, supplier_id, pbp_price_amount, pbp_currency, pbp_tax, pbp_lower_bound) VALUES ('" . $pbp_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($pbp_price_amount)) . "', '" . dbStr(trim($pbp_currency)) . "', '" . dbStr(trim($pbp_tax)) . "', '" . dbStr(trim($pbp_lower_bound)) . "') ") or die(mysqli_error($GLOBALS['conn']));
            }
        }

        if (!empty($pro_gallery)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM `products_gallery` WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_gallery); $i++) {
                $pg_mime_type = $pro_gallery[$i]->MIME_TYPE;
                $pg_mime_source = basename($pro_gallery[$i]->MIME_SOURCE);
                $pg_mime_source_url = $pro_gallery[$i]->MIME_SOURCE;
                $pg_mime_description = $pro_gallery[$i]->MIME_DESCR;
                $pg_mime_alt = $pro_gallery[$i]->MIME_ALT;
                $pg_mime_purpose = $pro_gallery[$i]->MIME_PURPOSE;
                $pg_mime_order = $pro_gallery[$i]->MIME_ORDER;
                //print($i.": pg_mime_type = ".$pg_mime_type." pg_mime_source = ".$pg_mime_source." pg_mime_description = ".$pg_mime_description." pg_mime_alt = ".$pg_mime_alt." pg_mime_order =".$pg_mime_order."<br>");
                $pg_id = getMaximum("products_gallery", "pg_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO products_gallery (pg_id, pro_id, supplier_id, pg_mime_type, pg_mime_source, pg_mime_source_url, pg_mime_description, pg_mime_alt, pg_mime_purpose, pg_mime_order) VALUES ('" . $pg_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($pg_mime_type)) . "', '" . dbStr(trim($pg_mime_source)) . "', '" . dbStr(trim($pg_mime_source_url)) . "', '" . dbStr(trim($pg_mime_description)) . "', '" . dbStr(trim($pg_mime_alt)) . "', '" . dbStr(trim($pg_mime_purpose)) . "', '" . dbStr(trim($pg_mime_order)) . "') ") or die(mysqli_error($GLOBALS['conn']));
            }
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnImportQuantity'])) {
    $file = $_FILES['doc_ImportQuantity']['tmp_name'];
    $ext = pathinfo($_FILES['doc_ImportQuantity']['name'], PATHINFO_EXTENSION);
    if (in_array($ext, array("csv")) && !empty($file)) {
        $csvFilePath = $file;
        $file = fopen($csvFilePath, "r");
        $i = 0;
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($i > 0) {
                $row_data = explode(";", $row[0]);
                //print_r($row_data);die();
                $pq_id = getMaximum("products_quantity", "pq_id");
                $supplier_id = dbStr(trim($row_data[0]));
                $pq_quantity = dbStr(trim($row_data[1]));
                $pq_upcomming_quantity = dbStr(trim($row_data[2]));
                $pq_status = dbStr(trim($row_data[3]));

                $Query = "SELECT * FROM products_quantity WHERE  supplier_id = '" . dbStr(trim($supplier_id)) . "' ";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    $row = mysqli_fetch_object($rs);
                    $pq_id = $row->pq_id;
                    mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET pq_quantity = '" . $pq_quantity . "', pq_upcomming_quantity = '" . $pq_upcomming_quantity . "', pq_status = '" . $pq_status . "' WHERE pq_id = '" . $pq_id . "'") or die(mysqli_error($GLOBALS['conn']));
                } else {
                    mysqli_query($GLOBALS['conn'], "INSERT INTO products_quantity (pq_id, supplier_id, pq_quantity, pq_upcomming_quantity, pq_status) VALUES ('" . $pq_id . "', '" . $supplier_id . "', '" . $pq_quantity . "', '" . $pq_upcomming_quantity . "', '" . $pq_status . "')") or die(mysqli_error($GLOBALS['conn']));
                }
            }
            $i++;
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?file&" . $qryStrURL . "op=1");
        //print("<br>Completed");
        //die();
    } elseif (empty($file)) {
        $class = "alert alert-info";
        $strMSG = "Please Select the file";
    } else {
        $class = "alert alert-danger";
        $strMSG = "Plz select the correct file and convert the file with the help of that link: <a target='_blank' href = 'https://convertio.co/'>Convertio</a> ";
    }
    //header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnImportSpecialprice'])) {
    //print_r($_REQUEST);die();
    $file = $_FILES['doc_ImportSpecialprice']['tmp_name'];
    $ext = pathinfo($_FILES['doc_ImportSpecialprice']['name'], PATHINFO_EXTENSION);
    if (in_array($ext, array("csv")) && !empty($file)) {
        $csvFilePath = $file;
        $file = fopen($csvFilePath, "r");
        $i = 0;
        while (($row = fgetcsv($file)) !== FALSE) {
            if ($i > 0) {
                $row_data = explode(";", $row[0]);
                //print_r($row);die();
                $supplier_id = dbStr(trim($row[0]));
                $lower_bound_one_price =  dbStr(trim($row[1]));
                $lower_bound_two_quantity = dbStr(trim($row[2]));
                $lower_bound_three_quantity = dbStr(trim($row[3]));
                $lower_bound_four_quantity = dbStr(trim($row[4]));
                $lower_bound_two_price = dbStr(trim($row[5]));
                $lower_bound_three_price = dbStr(trim($row[6]));
                $lower_bound_four_price = dbStr(trim($row[7]));

                $Query1 = "SELECT * FROM products_bundle_price WHERE  supplier_id = '" . dbStr(trim($supplier_id)) . "' AND pbp_lower_bound = '1' ";
                $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                if (mysqli_num_rows($rs1) > 0) {
                    $row1 = mysqli_fetch_object($rs1);
                    $pbp_id1 = $row1->pbp_id;
                    mysqli_query($GLOBALS['conn'], "UPDATE products_bundle_price SET pbp_special_price_amount = '" . str_replace(",", ".", $lower_bound_one_price) . "' WHERE pbp_id = '" . $pbp_id1 . "'") or die(mysqli_error($GLOBALS['conn']));
                }

                if ($lower_bound_two_quantity > 0) {
                    $Query2 = "SELECT * FROM products_bundle_price WHERE  supplier_id = '" . dbStr(trim($supplier_id)) . "' AND pbp_lower_bound = '" . $lower_bound_two_quantity . "' ";
                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                    if (mysqli_num_rows($rs2) > 0) {
                        $row2 = mysqli_fetch_object($rs2);
                        $pbp_id2 = $row2->pbp_id;
                        mysqli_query($GLOBALS['conn'], "UPDATE products_bundle_price SET pbp_special_price_amount = '" . str_replace(",", ".", $lower_bound_two_price) . "' WHERE pbp_id = '" . $pbp_id2 . "'") or die(mysqli_error($GLOBALS['conn']));
                    }
                }

                if ($lower_bound_three_quantity > 0) {
                    $Query3 = "SELECT * FROM products_bundle_price WHERE  supplier_id = '" . dbStr(trim($supplier_id)) . "' AND pbp_lower_bound = '" . $lower_bound_three_quantity . "' ";
                    $rs3 = mysqli_query($GLOBALS['conn'], $Query3);
                    if (mysqli_num_rows($rs3) > 0) {
                        $row3 = mysqli_fetch_object($rs3);
                        $pbp_id3 = $row3->pbp_id;
                        mysqli_query($GLOBALS['conn'], "UPDATE products_bundle_price SET pbp_special_price_amount = '" . str_replace(",", ".", $lower_bound_three_price) . "' WHERE pbp_id = '" . $pbp_id3 . "'") or die(mysqli_error($GLOBALS['conn']));
                    }
                }

                if ($lower_bound_four_quantity > 0) {
                    $Query4 = "SELECT * FROM products_bundle_price WHERE  supplier_id = '" . dbStr(trim($supplier_id)) . "' AND pbp_lower_bound = '" . $lower_bound_four_quantity . "' ";
                    $rs4 = mysqli_query($GLOBALS['conn'], $Query4);
                    if (mysqli_num_rows($rs4) > 0) {
                        $row4 = mysqli_fetch_object($rs4);
                        $pbp_id4 = $row4->pbp_id;
                        mysqli_query($GLOBALS['conn'], "UPDATE products_bundle_price SET pbp_special_price_amount = '" . str_replace(",", ".", $lower_bound_four_price) . "' WHERE pbp_id = '" . $pbp_id4 . "'") or die(mysqli_error($GLOBALS['conn']));
                    }
                }
            }
            $i++;
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?file&" . $qryStrURL . "op=1");
        //print("<br>Completed");
        //die();
    } elseif (empty($file)) {
        $class = "alert alert-info";
        $strMSG = "Please Select the file";
    } else {
        $class = "alert alert-danger";
        $strMSG = "Plz select the correct file and convert the file with the help of that link: <a target='_blank' href = 'https://convertio.co/'>Convertio</a> ";
    }
    //header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {

    mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_description_short = '" . dbStr(trim($_REQUEST['pro_description_short'])) . "',  pro_description_long='" . dbStr(trim($_REQUEST['pro_description_long'])) . "' WHERE pro_id= '" . $_REQUEST['pro_id'] . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    for ($i = 0; $i < count($_REQUEST['pk_id']); $i++) {
        mysqli_query($GLOBALS['conn'], "UPDATE products_keyword SET pk_title = '" . dbStr(trim($_REQUEST['pk_title'][$i])) . "' WHERE pk_id = '" . $_REQUEST['pk_id'][$i] . "' AND pro_id= '" . $_REQUEST['pro_id'] . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT cm.*, cat.cat_title_de AS cat_title, pro.pro_id, pro.pro_description_short, pro.pro_description_long, pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN category AS cat ON cat.group_id = cm.cat_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE pro.pro_id = '" . $_REQUEST['pro_id'] . "' AND pro.supplier_id = '" . $_REQUEST['supplier_id'] . "' ");
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $cat_id = $rsMem->cat_id;
            $sub_group_ids = explode(",", $rsMem->sub_group_ids);
            //print_r($sub_group_ids);die();

            $cat_title_one = returnName("cat_title_de AS cat_title", "category", "group_id", $sub_group_ids[0]);
            $cat_title_two = returnName("cat_title_de AS cat_title", "category", "group_id", $sub_group_ids[1]);
            $cat_title_three = $rsMem->cat_title;
            $pro_description_short = $rsMem->pro_description_short;
            $pro_description_long = $rsMem->pro_description_long;
            $mfile_path = !empty($rsMem->pg_mime_source_url) ? get_image_link(427, $rsMem->pg_mime_source_url) : "";
            //$mfile_path = !empty($rsMem->pg_mime_source_url) ? "" : "";
            $formHead = "Update Info";
        }
    } elseif ($_REQUEST['action'] == 3) {
        $formHead = "Add Quantity of ";
    } elseif ($_REQUEST['action'] == 4) {
        $formHead = "Add New Schulranzen of ";
    } elseif ($_REQUEST['action'] == 5) {
        $formHead = "Add New Special Price of ";
    } else {
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status='1' WHERE pro_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button InActive--------------------
if (isset($_REQUEST['btnInactive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status='0' WHERE pro_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
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
                <div class="alert alert-success" id="success" style="display: none;"> Record Updated Successfully<a class="close" data-dismiss="alert">×</a></div>
                <div class="alert alert-danger" style="display: none;"> Record not Updated<a class="close" data-dismiss="alert">×</a></div>
                <?php if (isset($_REQUEST['action'])) { ?>
                    <div class="main_container">
                        <h2 class="text-white">
                            <?php print($formHead); ?> Product
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <?php if ($_REQUEST['action'] == 2) { ?>
                                <div class="row">
                                    <div class="col-md-12 col-12 mt-3">
                                        <img class="rounded" src="<?php print($mfile_path); ?>" width="30%" alt="">
                                    </div>
                                    <div class="col-md-4 col-12 mt-3">
                                        <label for="">Category ( Group Name One )</label>
                                        <input type="text" class="input_style" readonly name="cat_title_one" id="cat_title_one" value="<?php print($cat_title_one); ?>" placeholder="Category ( Group Name One )">
                                    </div>
                                    <div class="col-md-4 col-12 mt-3">
                                        <label for="">Category ( Group Name Two )</label>
                                        <input type="text" class="input_style" readonly name="cat_title_two" id="cat_title_two" value="<?php print($cat_title_two); ?>" placeholder="Category ( Group Name Two )">
                                    </div>
                                    <div class="col-md-4 col-12 mt-3">
                                        <label for="">Category ( Group Name Three )</label>
                                        <input type="text" class="input_style" readonly name="cat_title_three" id="cat_title_three" value="<?php print($cat_title_three); ?>" placeholder="Category ( Group Name Three )">
                                    </div>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">Short Description</label>
                                        <input type="text" class="input_style" name="pro_description_short" id="pro_description_short" value="<?php print($pro_description_short); ?>" placeholder="Short Description">
                                    </div>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">Long Description</label>
                                        <textarea type="text" class="input_style" name="pro_description_long" id="pro_description_long" placeholder="Long Description"> <?php print($pro_description_long); ?> </textarea>
                                    </div>
                                    <?php
                                    $counter = 0;
                                    $Query = "SELECT * FROM `products_keyword` WHERE pro_id = '" . $_REQUEST['pro_id'] . "' AND supplier_id = '" . $_REQUEST['supplier_id'] . "'";
                                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $counter++;
                                    ?>
                                            <div class="col-md-3 col-12 mt-3">
                                                <label for="">Keywowd <?php print($counter); ?> </label>
                                                <input type="hidden" name="pk_id[]" id="pk_id" value="<?php print($row->pk_id); ?>">
                                                <input type="text" class="input_style" name="pk_title[]" id="pk_title" value="<?php print($row->pk_title); ?>" placeholder="Keywowd">
                                            </div>
                                    <?php
                                        }
                                    }
                                } elseif ($_REQUEST['action'] == 3) { ?>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">File</label>
                                        <div class="">
                                            <label for="file-upload" class="upload-btn">
                                                <span class="material-icons">cloud_upload</span>
                                                <span>Upload Files</span>
                                            </label>
                                            <input id="file-upload" type="file" class="file-input" name="doc_ImportQuantity">
                                        </div>
                                    </div>
                                <?php } elseif ($_REQUEST['action'] == 5) { ?>
                                    <div class="col-md-12 col-12 mt-3">
                                        <label for="">File</label>
                                        <div class="">
                                            <label for="file-upload" class="upload-btn">
                                                <span class="material-icons">cloud_upload</span>
                                                <span>Upload Files</span>
                                            </label>
                                            <input id="file-upload" type="file" class="file-input" name="doc_ImportSpecialprice">
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ($_REQUEST['action'] == 2) { ?>
                                    <div class="padding_top_bottom mt-3">
                                        <button class="btn btn-primary" type="submit" name="btnUpdate" id="btnImport">Update</button>
                                    <?php } else {
                                    $btn_name = "btnImport";
                                    if ($_REQUEST['action'] == 3) {
                                        $btn_name = "btnImportQuantity";
                                    } elseif ($_REQUEST['action'] == 5) {
                                        $btn_name = "btnImportSpecialprice";
                                    } elseif ($_REQUEST['action'] == 4) {
                                        $btn_name = "btnImportSchulranzen";
                                    }
                                    ?>
                                        <div class=" <?php print(($_REQUEST['action'] == 1 || $_REQUEST['action'] == 4) ? 'text_align_center' : ''); ?> mt-3">
                                            <button class="btn btn-primary" type="submit" name="<?php print($btn_name); ?>" id="btnImport">Upload</button>
                                        <?php } ?>
                                        <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="table-controls">
                            <h1 class="text-white">Artical Management</h1>
                            <div class="d-flex gap-1">
                                <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=3"); ?>" class="add-new"><span class="material-icons icon">upload</span> <span class="text">Import Quantity</span></a>
                                <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=5"); ?>" class="add-new"><span class="material-icons icon">upload</span> <span class="text">Import Special Price</span></a>
                                <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">upload</span> <span class="text">Upload New Artical</span></a>
                                <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=4"); ?>" class="add-new"><span class="material-icons icon">upload</span> <span class="text">Upload New Schulranzen</span></a>
                                <a href="<?php print("google_merchant_xml.php"); ?>" class="add-new"><span class="material-icons icon">download</span> <span class="text">Export Google Merchent Data</span></a>
                            </div>

                        </div>
                    </div>
                    <div class="main_table_container">
                        <?php
                        $pro_id = 0;
                        $pro_status = 0;
                        $pro_description_short = "";
                        $searchQuery = "";

                        if (isset($_REQUEST['pro_id']) && $_REQUEST['pro_id'] > 0) {
                            if (!empty($_REQUEST['pro_description_short'])) {
                                $pro_id = $_REQUEST['pro_id'];
                                $pro_description_short = $_REQUEST['pro_description_short'];
                                $searchQuery .= " AND pro.pro_id = '" . $_REQUEST['pro_id'] . "'";
                            }
                        }
                        if (isset($_REQUEST['pro_status']) && $_REQUEST['pro_status'] > 0) {
                            $pro_status = $_REQUEST['pro_status'];
                            if ($pro_status == 1) {
                                $searchQuery .= " AND pro.pro_status = '1'";
                            } else {
                                $searchQuery .= " AND pro.pro_status = '0'";
                            }
                        }
                        ?>
                        <form class="row flex-row" name="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-4 col-12 mt-2">
                                <label for="" class="text-white">Title</label>
                                <input type="hidden" name="pro_id" id="pro_id" value="<?php print($pro_id); ?>">
                                <input type="text" class="input_style pro_description_short" name="pro_description_short" id="pro_description_short" value="<?php print($pro_description_short); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Status</label>
                                <select name="pro_status" id="pro_status" class="input_style" onchange="javascript: frm_search.submit();">
                                    <option value="0" <?php print(($pro_status == 0) ? 'selected' : ''); ?>>N/A</option>
                                    <option value="1" <?php print(($pro_status == 1) ? 'selected' : ''); ?>>Live</option>
                                    <option value="2" <?php print(($pro_status == 2) ? 'selected' : ''); ?>>Offline</option>
                                </select>
                            </div>
                        </form>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="10"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th width="100">Image</th>
                                        <th width="100">Artical Id</th>
                                        <th>Title </th>
                                        <th style="text-align: right; width: 256px">Stock</th>
                                        <th style="text-align: right; width: 185px">Normal Price</th>
                                        <th style="text-align: right; width: 185px">Special Price</th>
                                        <th width="50">Status</th>
                                        <th width="110">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT pro.*, pg.pg_mime_source_url, pq.pq_id, pq.pq_quantity, pq.pq_upcomming_quantity, pq.pq_status FROM products AS pro LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) LEFT OUTER JOIN products_quantity AS pq ON pq.supplier_id = pro.supplier_id WHERE pro.pro_custom_add = '0' " . $searchQuery . " ORDER BY pro.pro_id ASC";
                                    //$Query = "SELECT pro.*, pq.pq_id, pq.pq_quantity, pq.pq_upcomming_quantity, pq.pq_status FROM products AS pro LEFT OUTER JOIN products_quantity AS pq ON pq.supplier_id = pro.supplier_id " . $searchQuery . " ORDER BY pro.pro_id ASC";
                                    //print($Query);
                                    $counter = 0;
                                    $limit = 25;
                                    $start = $p->findStart($limit);
                                    $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                    $pages = $p->findPages($count, $limit);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $counter++;
                                            $strClass = 'label  label-danger';
                                            $image_path = $GLOBALS['siteURL'] . "files/no_img_1.jpg";

                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->pro_id); ?>"></td>
                                                <td>
                                                    <div class="popup_container">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->supplier_id); ?></td>
                                                <td><?php print($row->pro_description_short); ?></td>
                                                <td>
                                                    <div class="table-box-body">
                                                        <input type="hidden" name="pro_id" id="pro_id_<?php print($counter); ?>" value="<?php print($row->pro_id); ?>">
                                                        <input type="hidden" name="supplier_id" id="supplier_id_<?php print($counter); ?>" value="<?php print($row->supplier_id); ?>">
                                                        <input type="hidden" name="pq_id" id="pq_id_<?php print($counter); ?>" value="<?php print($row->pq_id); ?>">
                                                        <div class="table-form-group">
                                                            <label for="">Auf Lager</label>
                                                            <input type="number" name="pq_quantity" id="pq_quantity_<?php print($counter); ?>" value="<?php print($row->pq_quantity); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min="0">
                                                        </div>
                                                        <div class="table-form-group">
                                                            <label for="">Online verfügbar</label>
                                                            <input type="number" name="pq_upcomming_quantity" id="pq_upcomming_quantity_<?php print($counter); ?>" value="<?php print($row->pq_upcomming_quantity); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min="0">
                                                        </div>
                                                        <div class="table-form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="button" name="pro_update_quantity" data-id="<?php print($counter); ?>" class="btn btn-success btn-style-light w-auto pro_update_quantity" value="Update (<?php print(($row->pq_status == "true") ? 'T' : 'F'); ?>)">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-box-body">
                                                        <?php
                                                        $counter1 = 0;
                                                        $Query1 = "SELECT * FROM `products_bundle_price` WHERE pro_id = '" . $row->pro_id . "' AND supplier_id = '" . $row->supplier_id . "' ORDER BY pbp_lower_bound ASC";
                                                        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                                                        if (mysqli_num_rows($rs1) > 0) {
                                                            while ($row1 = mysqli_fetch_object($rs1)) {
                                                                $counter1++;
                                                        ?>
                                                                <div class="table-form-group">
                                                                    <input type="hidden" name="pbp_id" id="pbp_id_<?php print($counter); ?>_<?php print($counter1); ?>" value="<?php print($row1->pbp_id); ?>">
                                                                    <label for="">LB <?php print($row1->pbp_lower_bound) ?> </label>
                                                                    <input type="number" step="any" name="pbp_price_amount[]" id="pbp_price_amount_<?php print($counter); ?>_<?php print($counter1); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min="0" value="<?php print($row1->pbp_price_amount) ?>">
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="table-form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="hidden" name="pro_update_price_lenght" id="pro_update_price_lenght_<?php print($counter); ?>" value="<?php print($counter1); ?>">
                                                            <input type="button" name="btnUpdatePrice" data-id="<?php print($counter); ?>" class="btn btn-success btn-style-light pro_update_price" value="Update">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-box-body">
                                                        <?php
                                                        $counter1 = 0;
                                                        $Query1 = "SELECT * FROM `products_bundle_price` WHERE pro_id = '" . $row->pro_id . "' AND supplier_id = '" . $row->supplier_id . "' ORDER BY pbp_lower_bound ASC";
                                                        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                                                        if (mysqli_num_rows($rs1) > 0) {
                                                            while ($row1 = mysqli_fetch_object($rs1)) {
                                                                $counter1++;
                                                        ?>
                                                                <div class="table-form-group">
                                                                    <input type="hidden" name="pbp_special_price_id" id="pbp_special_price_id_<?php print($counter); ?>_<?php print($counter1); ?>" value="<?php print($row1->pbp_id); ?>">
                                                                    <label for="">LB <?php print($row1->pbp_lower_bound) ?> </label>
                                                                    <input type="number" readonly step="any" name="pbp_special_price_amount[]" id="pbp_special_price_amount_<?php print($counter); ?>_<?php print($counter1); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min="0" value="<?php print($row1->pbp_special_price_amount) ?>">
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <!--<div class="table-form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="hidden" name="pro_update_special_price_lenght" id="pro_update_special_price_lenght_<?php print($counter); ?>" value="<?php print($counter1); ?>">
                                                            <input type="button" name="btnUpdateSpecialPrice" data-id="<?php print($counter); ?>" class="btn btn-success btn-style-light pro_update_special_price" value="Update">
                                                        </div>-->
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->pro_status == 0) {
                                                        echo '<span class="btn btn-danger w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL'] . "product_detail.php?supplier_id=" . $row->supplier_id); ?>');"><span class="material-icons icon material-xs">visibility</span></button>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pro_id=" . $row->pro_id . "&supplier_id=" . $row->supplier_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
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
                            <div class="row">
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnActive" value="Active" class="btn btn-primary btn-style-light w-auto">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-style-light w-auto">
                                </div>
                            </div>
                            <!--<input type="submit" name="btnDelete" onclick="return confirm('Are you sure you want to delete selected item(s)?');" value="Delete" class="btn btn-danger btn-style-light">-->
                        </form>

                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
    <script>
        $('input.pro_description_short').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'ajax_calls.php?action=pro_description_short',
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
                var pro_id = $("#pro_id");
                var pro_description_short = $("#pro_description_short");
                $(pro_id).val(ui.item.pro_id);
                $(pro_description_short).val(ui.item.value);
                frm_search.submit();
                //return false;
                //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
            }
        });
        $(".pro_update_quantity").on("click", function() {
            //console.log("btnUpdateQuantity");
            let pro_id = $("#pro_id_" + $(this).attr("data-id")).val();
            let supplier_id = $("#supplier_id_" + $(this).attr("data-id")).val();
            let pq_id = $("#pq_id_" + $(this).attr("data-id")).val();
            let pq_quantity = $("#pq_quantity_" + $(this).attr("data-id")).val();
            let pq_upcomming_quantity = $("#pq_upcomming_quantity_" + $(this).attr("data-id")).val();
            //console.log("pro_id: "+pro_id+" supplier_id: "+supplier_id+" pq_id: "+pq_id);
            $.ajax({
                url: 'ajax_calls.php?action=pro_update_quantity',
                method: 'POST',
                data: {
                    pro_id: pro_id,
                    supplier_id: supplier_id,
                    pq_id: pq_id,
                    pq_quantity: pq_quantity,
                    pq_upcomming_quantity: pq_upcomming_quantity
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    //console.log(obj);
                    if (obj.status == 1) {
                        $("#pq_quantity_" + $(this).attr("data-id")).val(obj.data[0].pq_quantity);
                        $("#pq_upcomming_quantity_" + $(this).attr("data-id")).val(obj.data[0].pq_upcomming_quantity);
                        $.toast({
                            heading: 'Success',
                            text: 'Stock updated successfully',
                            icon: 'success',
                            position: 'top-right'
                        });
                    }
                }
            });
        });
        $(".pro_update_price").on("click", function() {
            //console.log("btnUpdateQuantity");
            let priceData = [];
            let pro_id = $("#pro_id_" + $(this).attr("data-id")).val();
            let supplier_id = $("#supplier_id_" + $(this).attr("data-id")).val();
            let pro_update_price_lenght = $("#pro_update_price_lenght_" + $(this).attr("data-id")).val();
            for (let i = 1; i <= pro_update_price_lenght; i++) {
                //console.log("i: "+i);
                let pbp_id = $("#pbp_id_" + $(this).attr("data-id") + "_" + i).val();
                let pbp_price_amount = $("#pbp_price_amount_" + $(this).attr("data-id") + "_" + i).val();
                priceData.push({
                    pbp_id: pbp_id,
                    pbp_price_amount: pbp_price_amount
                });
            }
            //let pbp_id = $("#pbp_id_" + $(this).attr("data-id")).val();
            //let pbp_price_amount = $("#pbp_price_amount_" + $(this).attr("data-id")).val();
            //console.log("pro_id: "+pro_id+" supplier_id: "+supplier_id+" pbp_id: "+pbp_id+" pbp_price_amount: "+pbp_price_amount);
            $.ajax({
                url: 'ajax_calls.php?action=pro_update_price',
                method: 'POST',
                data: {
                    pro_id: pro_id,
                    supplier_id: supplier_id,
                    priceData: priceData
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    console.log(obj);
                    if (obj.status == 1) {
                        $.toast({
                            heading: 'Success',
                            text: 'Price updated successfully',
                            icon: 'success',
                            position: 'top-right'
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>