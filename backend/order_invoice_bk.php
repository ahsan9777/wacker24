<?php 
include("../lib/session_head.php");
$Query = "SELECT * FROM orders AS ord WHERE ord.ord_id = '".$_REQUEST['ord_id']."'";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if(mysqli_num_rows($rs) > 0){
    $row = mysqli_fetch_object($rs);
    $ord_id = $row->ord_id;
    $user_id = $row->user_id;
    $ord_datetime = date('D F j, Y', strtotime($row->ord_datetime));
    $ord_gross_total = $row->ord_gross_total;
    $ord_gst = $row->ord_gst;
    $ord_discount = $row->ord_discount;
    $ord_shipping_charges = $row->ord_shipping_charges;
    $ord_amount = number_format(($row->ord_amount + $row->ord_shipping_charges), "2", ",", "");
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
</head>

<body >
    <div class="container my-3 py-3" style="border:1px solid #aeaeae;background: #fff;">
        <table class="w-100" cellpadding="5" id="print" >
            <tr>
                <td width="70%" bgcolor="#c00418" class="text-white text-right">IHR BÜROVERSORGER VON A BIS Z</td>
                <td></td>
            </tr>
            <tr>
                <td width="70%"></td>
                <td class="text-black" ><img src="./assets/images/pdf_logo.png" width="100%"><br><br>
                    BÜROTECHNIK + SERVICE<br>
                    BÜROBEDARF<br>
                    BÜRO-EINRICHTUNG<br>
                    TECHNISCHER KUNDENDIENST<br>
                    COPYSHOP</td>
            </tr>
            <tr>
                <td width="70%" style="padding-left: 50px" class="text-black">
                    <b>Wacker Bürocenter GmbH</b> | Chemnitzer Straße 1 | 67433 Neustadt<br>
                    wacker<br>blockfield,26-30<br>67112 Mutterstadt<br>Deutschland
                </td>
                <td></td>
            </tr>
            <tr>
                <td><br><br><br>
                    <h3 class="text-black" style="padding-left: 50px">Rechnung</h3>
                </td>

            </tr>
            <tr>
                <td colspan="2">
                    <table class="w-100" >
                        <tr>
                            <th style="padding-left: 50px" width="20px"></th>
                            <th  width="30%" class="text-black">Steuer-Nr</th>
                            <th class="text-right text-black">Kunden-Nr</th>
                            <th class="text-right text-black">Rechnungs-Nr</th>
                            <th class="text-right text-black">Datum</th>
                            <th class="text-right text-black">Seite</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td width="30%"></td>
                            <td class="text-right text-black"> <?php print($user_id); ?> </td>
                            <td class="text-right text-black"> <?php print($ord_id); ?> </td>
                            <td class="text-right text-black"> <?php print($ord_datetime); ?> </td>;
                            <td class="text-right text-black">1</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="w-100" >
                        <tr>
                            <th class="text-black"></th>
                            <th class="text-black">Artikel-Nr</th>
                            <th class="text-black">Bezeichnung</th>
                            <th class="text-black">Preis</th>
                            <th class="text-black">Menge Einh</th>
                            <th class="text-black">PE Rab.</th>
                            <th class="text-black">Betrag</th>
                        </tr>
                        <?php
                        $counter = 0;
                        $Query = "SELECT oi.*, pro.pro_description_short FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id WHERE oi.ord_id =  '".$_REQUEST['ord_id']."' ORDER BY oi.oi_id ASC";
                        //print($Query);
                        $rs = mysqli_query($GLOBALS['conn'], $Query);
                        if (mysqli_num_rows($rs) > 0) {
                            while ($row = mysqli_fetch_object($rs)) {
                                $counter++;
                        ?>
                        <tr style="padding: 10px">
                            <td class="text-black"><?php print($counter); ?></td>
                            <td class="text-black"><?php print($row->supplier_id); ?></td>
                            <td width="30%" class="text-black"><?php print($row->pro_description_short); ?></td>
                            <td class="text-black"><?php print(str_replace(".", ",", $row->oi_amount)); ?></td>
                            <td class="text-black"><?php print($row->oi_qty); ?></td>
                            <td class="text-black">0</td>
                            <td class="text-black"><?php print(str_replace(".", ",", $row->oi_gross_total)); ?></td>
                        </tr>
                        <?php 
                            }
                        }
                        ?>
                    </table>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <table class="w-100" style="border-top: 2px solid #000;border-bottom: 2px solid #000">
                        <tr>
                            <th class="text-black">Zahlungsbedingungen</th>
                            <th class="text-black">Nettobetrag</th>
                            <th class="text-black">Mwstbetrag (<?php print(config_gst * 100); ?>%)</th>
                            <th class="text-black">Versand</th>
                            <th class="text-black">Rechnungsbetrag</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-black"> <?php print(str_replace(".", ",", $ord_gross_total)); ?> EUR</td>
                            <td class="text-black"> <?php print(str_replace(".", ",", $ord_gst)); ?> EUR</td>
                            <td class="text-black"> <?php print(str_replace(".", ",", $ord_shipping_charges)); ?> EUR</td>
                            <td class="text-black"> <?php print(str_replace(".", ",", $ord_amount)); ?> EUR</td>
                        </tr>
                    </table><br><br>
                    <table style="font-size: 10px" class="w-100">
                        <tr>
                            <td class="text-black">Geschäftsführer: Christian Wacker und Ursula Wacker<br>
                                Sitz der Gesellschaft: Neustadt/W.<br>
                                Amtsgericht Ludwigshafen/Rh. HRB 41564<br>
                                USt.-ID: DE 149 390 904</td>
                            <td class="text-black">VR-Bank Südpfalz:<br>
                                BIC: GENODE61SUW<br>
                                IBAN: DE95 5486 2500 0006 7025 70</td>
                            <td class="text-black">Sparkasse Rhein-Haardt:<br>
                                BIC: MALADE51DKH<br>
                                IBAN: DE67 5465 1240 1000 3079 24</td>
                            <td class="text-black">Wacker Bürocenter GmbH<br>
                                Chemnitzer Straße 1<br>
                                67433 Neustadt<br>
                                Telefon: 06321 9124-0<br>
                                Telefax: 06321 9124-99</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>