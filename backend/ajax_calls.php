<?php
include("../lib/openCon.php");
include("../lib/functions.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
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
