<?php
include("../lib/openCon.php");
include("../lib/functions.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {

        case 'cat_title':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] > 0){
                    $where .= " WHERE parent_id > '0' AND ( cat_title_de LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR cat_title_en LIKE '%" . dbStr($_REQUEST['term']) . "%')";
                } else{
                    $where .= " WHERE parent_id = '0' AND ( cat_title_de LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' OR cat_title_en LIKE '%" . dbStr($_REQUEST['term']) . "%')";
                }
                }
            $Query = "SELECT cat_id, cat_title_de AS cat_title FROM `category` " . $where . " ORDER BY cat_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                'cat_id' => strip_tags(html_entity_decode($row->cat_id , ENT_QUOTES, 'UTF-8')),
                'value' => strip_tags(html_entity_decode($row->cat_title, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;
        case 'btn_toggle':
            $retValue = array();
            //print_r($_REQUEST);die();
            $data_update = mysqli_query($GLOBALS['conn'], "UPDATE ".$_REQUEST['table']." SET ".$_REQUEST['set_field']." = '".$_REQUEST['set_field_data']."' WHERE ".$_REQUEST['where_field']." = " . $_REQUEST['id']) or die(mysqli_error($GLOBALS['conn']));
            if($data_update == true){
                $retValue = array("status" => "1", "message" => "Record Updated successfully");
            } else{
                $retValue = array("status" => "0", "message" => "Record not Updated");
            }
           
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'pro_description_short':
            $json = array();
            $where = "";
            if (isset($_REQUEST['term']) && $_REQUEST['term'] != '') {
                    $where .= " WHERE pro_description_short LIKE '%" . dbStr(trim($_REQUEST['term'])) . "%' ";
                }
            $Query = "SELECT pro_id, pro_description_short FROM products " . $where . " ORDER BY pro_id  LIMIT 0,20";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            while ($row = mysqli_fetch_object($rs)) {
                $json[] = array(
                'pro_id' => strip_tags(html_entity_decode($row->pro_id , ENT_QUOTES, 'UTF-8')),
                'value' => strip_tags(html_entity_decode($row->pro_description_short, ENT_QUOTES, 'UTF-8'))
                );
            }
            $jsonResults = json_encode($json);
            print($jsonResults);
            break;
        
        case 'pro_update_quantity':
            $retValue = array();
            //print_r($_REQUEST);die();
            $data_update = mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET pq_quantity = '".dbStr(trim($_REQUEST['pq_quantity']))."', pq_upcomming_quantity = '".dbStr(trim($_REQUEST['pq_upcomming_quantity']))."' WHERE pq_id = '".dbStr(trim($_REQUEST['pq_id']))."' AND supplier_id = '".dbStr(trim($_REQUEST['supplier_id']))."' ") or die(mysqli_error($GLOBALS['conn']));
            if($data_update == true){
                $retValue = array("status" => "1", "message" => "Record Updated successfully");
                $retValue['data'][] = array(
                    "pq_quantity" => strval($_REQUEST['pq_quantity']),
                    "pq_upcomming_quantity" => strval($_REQUEST['pq_upcomming_quantity'])
                );
            } else{
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
            for($i = 0; $i < count($_REQUEST['priceData']); $i++){
                $data_update = mysqli_query($GLOBALS['conn'], "UPDATE products_bundle_price SET pbp_price_amount = '".dbStr(trim($_REQUEST['priceData'][$i]['pbp_price_amount']))."' WHERE pbp_id = '".dbStr(trim($_REQUEST['priceData'][$i]['pbp_id']))."' AND pro_id = '".dbStr(trim($_REQUEST['pro_id']))."' AND supplier_id = '".dbStr(trim($_REQUEST['supplier_id']))."' ") or die(mysqli_error($GLOBALS['conn']));
                $count++;
            }
            if($data_update == true){
                $retValue = array("status" => "1", "message" => "Record Updated successfully");
            } else{
                $retValue = array("status" => "0", "message" => "Record not Updated");
            }
           
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        }
}
