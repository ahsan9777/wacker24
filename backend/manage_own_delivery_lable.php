<?php 
include("../lib/session_head.php");
$Query = "SELECT orid.*, di.dinfo_additional_info, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id FROM order_return_item_detail AS orid LEFT OUTER JOIN delivery_info AS di ON di.ord_id = orid.ord_id WHERE orid.orid_id = '".$_REQUEST['orid_id']."'";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if(mysqli_num_rows($rs) > 0){
    $row = mysqli_fetch_object($rs);
    $delivery_info = "";
    if (!empty($row->deliver_full_name)) {
        $delivery_info .= $row->deliver_full_name . "<br>";
    }
    if (!empty($row->dinfo_additional_info)) {
        $delivery_info .= $row->dinfo_additional_info . "<br>";
    } elseif (!empty($user_company_name)) {
        $delivery_info .= $user_company_name . "<br>";
    }
    if (!empty($row->dinfo_street)) {
        $delivery_info .= $row->dinfo_street . " " . $row->dinfo_house_no . "<br>";
    }
    if (!empty($row->dinfo_usa_zipcode)) {
        $delivery_info .= $row->dinfo_usa_zipcode . "<br>Deutschland";
    }

    $orid_id = $row->orid_id;
    $ord_id = $row->ord_id;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WACKER Bürocenter GmbH - ecodirect Retourenschein</title>

<style>
    *{
        box-sizing:border-box;
        font-family:Arial, Helvetica, sans-serif;
    }

    body{
        background:#f4f4f4;
        margin:20px;
    }

    .page{
        width:210mm;
        min-height:297mm;
        margin:auto;
        background:#fff;
        border:1px solid #ccc;
        padding:20px;
    }

    .header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        border-bottom:3px solid #006633;
        padding-bottom:12px;
        margin-bottom:20px;
    }

    .logo h1{
        margin:0;
        color:#006633;
        font-size:30px;
    }

    .logo p{
        margin:4px 0 0;
        color:#555;
        font-size:15px;
        font-weight:bold;
    }

    .section{
        border:1px solid #bbb;
        padding:15px;
        margin-bottom:18px;
    }

    .section h2{
        margin-top:0;
        font-size:18px;
        background:#006633;
        color:#fff;
        padding:8px;
    }

    .addresses{
        display:flex;
        justify-content:space-between;
        gap:20px;
    }

    .address{
        width:48%;
        border:1px solid #ccc;
        padding:12px;
    }

    .address h3{
        margin-top:0;
        color:#006633;
    }

    table{
        width:100%;
        border-collapse:collapse;
        margin-top:15px;
    }

    table td{
        border:1px solid #ccc;
        padding:10px;
    }

    .barcode{
        margin-top:25px;
        border:2px dashed #000;
        height:90px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:18px;
        letter-spacing:4px;
        font-weight:bold;
    }

    .footer{
        margin-top:25px;
        text-align:center;
        font-size:13px;
        color:#555;
    }

    @media print{
        body{
            background:white;
            margin:0;
        }

        .page{
            border:none;
            width:100%;
            min-height:auto;
        }
    }
    .product_detail_table thead tr th{text-align: left;}
    .product_detail_table tbody tr td{ border: none;}
</style>

</head>
<body>

<div class="page">

    <div class="header">
        <div class="logo">
            <h1>WACKER Bürocenter GmbH</h1>
            <p>ecodirect – Lieferung mit eigenem Fuhrpark</p>
        </div>
    </div>

    <div class="section">
        <h2>Retourenschein</h2>

        <div class="addresses">

            <div class="address">
                <h3>Absender</h3>
                <?php print($delivery_info); ?>
            </div>

            <div class="address">
                <h3>Empfänger</h3>

                WACKER Bürocenter GmbH<br>
                Lager / Retoure<br>
                Chemnitzer Str. 1<br>
                67433 Neustadt<br>
                Deutschland

            </div>

        </div>

        <table>
            <tr>
                <td><strong>Lieferscheinnummer</strong></td>
                <td><?php print($ord_id); ?></td>
            </tr>

            <tr>
                <td><strong>Retourennummer</strong></td>
                <td><?php print($orid_id); ?></td>
            </tr>

            <tr>
                <td><strong>Versandart</strong></td>
                <td>ecodirect – Lieferung mit eigenem Fuhrpark</td>
            </tr>

        </table>
        <style>
            
        </style>
        <table class="product_detail_table">
            <thead>
                <tr>
                    <th style="width: 125px;">Image</th>
                    <th style="width: 125px;">Supplier ID</th>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Query = "SELECT orid.*, pro.pro_custom_add, pro.pro_description_short, pg.pg_mime_source_url FROM order_return_item_detail AS orid LEFT OUTER JOIN products AS pro ON pro.supplier_id = orid.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE orid.orid_id =  '" . $_REQUEST['orid_id'] . "' ORDER BY orid.orid_id ASC";
                //print($Query);
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                         $pg_mime_source_url = get_image_link(160, $row->pg_mime_source_url);
                        $pro_title = $row->pro_description_short;
                        if ($row->pro_custom_add > 0) {
                            $pg_mime_source_url = $GLOBALS['siteURL'] . $row->pg_mime_source_url;
                        }
                ?>
                <tr>
                    <td><img src="<?php print($pg_mime_source_url); ?>" alt="" srcset="" style = "width: 75px"></td>
                    <td><?php print($row->supplier_id); ?></td>
                    <td><?php print($pro_title); ?></td>
                </tr>
                <?php 
                    }
                }
                ?>
            </tbody>
        </table>

    </div>

    <div class="section">

        <h2>Versandinformationen</h2>

        <p>
            Bitte befestigen Sie diesen Retourenschein gut sichtbar auf Ihrer
            Rücksendung. Entfernen Sie zuvor alte Versandetiketten und Barcodes.
        </p>

        <p>
            Die Rücksendung wird durch den eigenen Fuhrpark der
            <strong>WACKER Bürocenter GmbH</strong> abgeholt oder zugestellt.
        </p>

    </div>

    <div class="footer">

        <strong>WACKER Bürocenter GmbH</strong><br>
        ecodirect – Lieferung mit eigenem Fuhrpark

    </div>

</div>

</body>
</html>