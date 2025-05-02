<?php
include '../lib/openCon.php';
include '../lib/functions.php';
$address_tag_data = "";
$ord_id = $_REQUEST['ord_id'];
$Query = "SELECT ord.*, u.user_id, u.customer_id, di.dinfo_email, CONCAT(di.dinfo_street, ' ', di.dinfo_house_no) AS user_street_house, di.dinfo_usa_zipcode, c.countries_iso_code_2 FROM orders AS ord LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = ord.ord_id LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id WHERE ord.ord_id = '".$ord_id."'";
$rs = $pdo->query($Query);
if ($rs->rowCount() > 0) {
    $row = $rs->fetch(PDO::FETCH_OBJ);
    $party_id = ( (empty($row->customer_id)) ? '600000' : $row->customer_id );
    $ord_id = $row->ord_id;
    $ord_datetime = date("Y-m-d", strtotime($row->ord_datetime));
    $ord_note = $row->ord_note;
    $order_total = $row->ord_amount;
    $dinfo_email = $row->dinfo_email;
    $user_street_house = $row->user_street_house;
    $dinfo_usa_zipcode = $row->dinfo_usa_zipcode;
    preg_match('/\d+/', $dinfo_usa_zipcode, $matches);

    if (!empty($matches)) {
        $postal_code = $matches[0];
        $street = ltrim($dinfo_usa_zipcode, $postal_code." ");
    } else{
        $postal_code = "";
        $street = $dinfo_usa_zipcode; 
    }
    $countries_iso_code_2 = $row->countries_iso_code_2;
    $address_tag_data = '
                <ADDRESS>
                     <NAME>Sanitätshaus Strack GmbH  Frau Mandy Walk</NAME>
                     <DEPARTMENT/>
                     <CONTACT>
                        <CONTACT_NAME>'.$dinfo_email.'</CONTACT_NAME>
                        <FAX/>
                        <EMAIL>'.$dinfo_email.'</EMAIL>
                     </CONTACT>
                     <STREET>'.$user_street_house.'</STREET>
                     <ZIP>'.$postal_code.'</ZIP>
                     <CITY>'.$street.'</CITY>
                     <COUNTRY>'.$countries_iso_code_2.'</COUNTRY>
                     <PHONE type="office">06341926032</PHONE>
                  </ADDRESS>
                  ';
}

header("Content-type: text/xml");
print('<?xml version="1.0" encoding="UTF-8" ?>');
print('<ORDER version="1.0" type="standard">');
print('<ORDER_HEADER>');
print('<ORDER_INFO>');
print('<ORDER_ID>'.$ord_id.'</ORDER_ID>');
print('<ORDER_DATE>'.$ord_datetime.'</ORDER_DATE>');
print('<ORDER_PARTIES>');

print('<BUYER_PARTY>');
print('<PARTY>');
print('<PARTY_ID type="buyer_specific">'.$party_id.'</PARTY_ID>');
print($address_tag_data);
print('</PARTY>');
print('</BUYER_PARTY>');

print('<SUPPLIER_PARTY>');
print('<PARTY>');
print('</PARTY>');
print('</SUPPLIER_PARTY>');

print('<INVOICE_PARTY>');
print('<PARTY>');
print($address_tag_data);
print('</PARTY>');
print('</INVOICE_PARTY>');

print('<SHIPMENT_PARTIES>');
print('<DELIVERY_PARTY>');
print('<PARTY>');
print($address_tag_data);
print('</PARTY>');
print('</DELIVERY_PARTY>');
print('</SHIPMENT_PARTIES>');

print('</ORDER_PARTIES>');

print('<REMARK type="deliverynote">'.$ord_note.'</REMARK>
         <HEADER_UDX>
            <UDX.SOE.GUTSCHEINRABATTWERT>0</UDX.SOE.GUTSCHEINRABATTWERT>
            <UDX.SOE.GUTSCHEINRABATTART>0</UDX.SOE.GUTSCHEINRABATTART>
            <UDX.SOE.GUTSCHEINRABATTSUMME>0</UDX.SOE.GUTSCHEINRABATTSUMME>
            <UDX.SOE.GUTSCHEINRABATTSUMMEHALBMWST>0</UDX.SOE.GUTSCHEINRABATTSUMMEHALBMWST>
            <UDX.SOE.GUTSCHEINRABATTSUMMEVOLLMWST>0</UDX.SOE.GUTSCHEINRABATTSUMMEVOLLMWST>
         </HEADER_UDX>');
print('</ORDER_INFO>');
print('</ORDER_HEADER>');

print('<ORDER_ITEM_LIST>');

$count = 0;
$Query = "SELECT oi.*, pro.pro_description_short, pro.pro_order_unit FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id WHERE oi.ord_id = '".$ord_id."'";
$rs = $pdo->query($Query);
if ($rs->rowCount() > 0) {
    while ($row = $rs->fetch(PDO::FETCH_OBJ)){
        $count++;
        $oi_amount = $row->oi_amount;
        $oi_gst_value = $row->oi_gst_value;
        $gst = ($oi_amount * $oi_gst_value);
        $price_amount = number_format( ($oi_amount + $gst), "2", ".", "" );
        $price_amount = sprintf("%08.2f", $price_amount);
print('<ORDER_ITEM>
         <LINE_ITEM_ID>'.$count.'</LINE_ITEM_ID>
         <ARTICLE_ID>
            <SUPPLIER_AID>'.$row->supplier_id.'</SUPPLIER_AID>
            <BUYER_AID type="BZRNR"/>
            <DESCRIPTION_SHORT>'.$row->pro_description_short.'/</DESCRIPTION_SHORT>
         </ARTICLE_ID>
         <QUANTITY>'.$row->oi_qty.'</QUANTITY>
         <ORDER_UNIT>'.$row->pro_order_unit.'</ORDER_UNIT>
         <ARTICLE_PRICE type="net_list">
            <PRICE_AMOUNT>'.$price_amount.'</PRICE_AMOUNT>
            <PRICE_LINE_AMOUNT>'.$row->oi_net_total.'</PRICE_LINE_AMOUNT>
            <PRICE_QUANTITY>1</PRICE_QUANTITY>
         </ARTICLE_PRICE>
         <ACCOUNTING_INFO>
            <COST_CATEGORY_ID/>
         </ACCOUNTING_INFO>
      </ORDER_ITEM>');
    }
}
print('<ORDER_SUMMARY>
      <TOTAL_ITEM_NUM>'.$count.'</TOTAL_ITEM_NUM>
      <TOTAL_AMOUNT>'.$order_total.'</TOTAL_AMOUNT>
   </ORDER_SUMMARY>');
print('</ORDER_ITEM_LIST>');

print('</ORDER>');
