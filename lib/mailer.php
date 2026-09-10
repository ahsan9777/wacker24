<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class Mailer
{

    public function test_attachment($cmessage)
    {
        $username = "";
        $password = "";
        $subject = "Mail Test";
        $to = "ahsannawaz9777@gmail.com";
        //$to2 = "hussaini@wacker-systems.de";
        //$to = "sayedkamalhussaini6@gmail.com";
        //$to = "ahsannawaz9777@gmx.com";
        //$to = " w-test@mail.de";

        $message = "Hi " . $cmessage . ",<br>
				<br>Hello<br>
				<br><br>Message:         
				<br><br>This is an automatic generated message. Do not reply to this message.";

        $fileUrl = $GLOBALS['siteURL'] . 'backend/manage_order_xml.php'; // Your file URL
        $tempFilePath = 'tempfile_' . uniqid() . '.xml'; // Temporary file to save

        // Download the file
        file_put_contents($tempFilePath, file_get_contents($fileUrl));
        //$this->sendEmail($username, $password, $to2, $subject, $message, 1, 0);
        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0, null, $tempFilePath);
        //print($ret);
        if (file_exists($tempFilePath)) {
            unlink($tempFilePath);
        }
    }
    public function test($cmessage)
    {
        $username = "";
        $password = "";
        $subject = "Mail Test";
        $to1 = "ahsannawaz9777@gmail.com";
        //$to2 = "hussaini@wacker-systems.de";
        //$to = "sayedkamalhussaini6@gmail.com";
        //$to = "ahsannawaz9777@gmx.com";
        //$to = " w-test@mail.de";

        $message = "Hi " . $cmessage . ",<br>
				<br>Hello<br>
				<br><br>Message:         
				<br><br>This is an automatic generated message. Do not reply to this message.";


        //$this->sendEmail($username, $password, $to2, $subject, $message, 1, 0);
        $this->sendEmail($username, $password, $to1, $subject, $message, 1, 0);
        //print($ret);
    }

    function  registration_account_verification($customer_name, $username, $password, $to, $subject_title, $user_verification_code)
    {

        //$get_email_template = json_encode();
        $get_email_template = json_decode(get_email_template("1"));
        // print("<pre>");
        // print_r($get_email_template);
        // print("</pre>");
        $subject = $get_email_template[0]->eml_subject;
        $subject = str_replace("{subject}", $subject_title, $subject);

        $eml_contents = $get_email_template[0]->eml_contents;
        $eml_contents = str_replace("{customer_name}", $customer_name, $eml_contents);
        $eml_contents = str_replace("{sitelogo}", $GLOBALS['siteURL'] . "images/register_logo.png", $eml_contents);
        $eml_contents = str_replace("{btnlink}", $GLOBALS['siteURL'] . "anmelden?verification_code=" . $user_verification_code, $eml_contents);
        $eml_contents = str_replace("{href_site}", $GLOBALS['siteURL'], $eml_contents);
        $eml_contents = str_replace("{siteName}", $GLOBALS['siteName'], $eml_contents);
        $message = $eml_contents;
        //print($message); die();

        return $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        /* print mail($To, $subject, $message, $headers);
         die;*/
    }

    public function order($ord_id)
    {

        //include("../backend/conn.php");

        $username = "";
        $password = "";
        $subject = "Bestellbestätigung";
        //$to = "ahsannawaz9777@gmail.com";

        $order_detail = "";
        $ord_gross_total = 0;
        $ord_gst = 0;
        $ord_shipping_charges = 0;
        $ord_amount = 0;
        //$Query = "SELECT oi.*, ord.user_id, ord.ord_datetime, ord.ord_udate, ord.ord_gross_total, ord.ord_gst, ord.ord_amount, ord.ord_shipping_charges, di.dinfo_countries_id, c.countries_name, di.dinfo_fname, di.dinfo_lname, di.dinfo_house_no, di.dinfo_street, di.dinfo_email, di.dinfo_usa_zipcode, di.dinfo_additional_info, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pg.pg_mime_source_url, pm.pm_title_de AS pm_title FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' AND pg.pg_mime_order = '1' LEFT OUTER JOIN payment_method AS pm ON pm.pm_id = ord.ord_payment_method WHERE ord.ord_id = '" . $ord_id . "' ORDER BY ord.ord_datetime DESC ";
        $Query = "SELECT oi.*, ord.user_id, ord.ord_datetime, ord.ord_udate, ord.ord_gross_total, ord.ord_gst, ord.ord_amount, ord.ord_shipping_charges, di.dinfo_countries_id, c.countries_name, di.dinfo_fname, di.dinfo_lname, di.dinfo_house_no, di.dinfo_street, di.dinfo_email, di.dinfo_usa_zipcode, di.dinfo_additional_info, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pg.pg_mime_source_url, pm.pm_title_de AS pm_title FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = oi.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) LEFT OUTER JOIN payment_method AS pm ON pm.pm_id = ord.ord_payment_method WHERE ord.ord_id = '" . $ord_id . "' ORDER BY ord.ord_datetime DESC ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            while ($row = mysqli_fetch_object($rs)) {
                $to = $row->dinfo_email;
                $ord_datetime = date('d/m/Y', strtotime($row->ord_datetime));
                $ord_id = $row->ord_id;
                $payment_method = $row->pm_title;
                $additional_info = $row->dinfo_additional_info;
                $house_and_street = $row->dinfo_house_no . " " . $row->dinfo_street;
                $location_and_country = $row->dinfo_usa_zipcode . ", " . $row->countries_name;
                $customer_name = $row->dinfo_fname . " " . $row->dinfo_lname;
                $ord_gross_total = $row->ord_gross_total;
                $ord_gst = $row->ord_gst;
                $ord_shipping_charges = $row->ord_shipping_charges;
                $ord_amount = number_format(($row->ord_amount + $ord_shipping_charges), "2", ",", "");
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                $supplier_id = $row->supplier_id;
                $pro_description_short = $row->pro_description_short;
                if($row->oi_type == 2) {
                    $supplier_id = "GRATIS fur Sie!";
                    $get_image_link = $GLOBALS['siteURL'] . "files/free_product/" .returnName("fp_file", "free_product", "fp_id", $row->fp_id);
                    $pro_description_short = returnName("fp_title_de AS fp_title", "free_product", "fp_id", $row->fp_id);
                }
                //echo $row->pro_image;die();

                $order_detail .= '<tr>
                    <td style="width: 200px; display: inline-block; margin-right: 20px;"><img src="' . $get_image_link . '" alt="" style="max-width: 100%; display: block; margin: auto; margin-bottom: 10px;"></td>
                    <td style="width: 370px; display: inline-block;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr><td colspan="3" style="font-size: 16px; color: #000; font-weight: bold; line-height: 130%;">' . $pro_description_short . '</td></tr>
                            <tr><td height="20"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Artikenummer:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px;font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . $supplier_id . '</td>
                            </tr>
                            <tr><td height="5"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Anzahl:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . $row->oi_qty . '</td>
                            </tr>
                            <tr><td height="5"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Einzelpreis:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . str_replace(".", ",", $row->oi_amount) . ' €</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td height="20"></td></tr>
                ';
            }
        } else {
            print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
        }

        $get_email_template = json_decode(get_email_template("2"));
        $message = $get_email_template[0]->eml_contents;;
        $message = str_replace("{logo}", $GLOBALS['siteURL'] . "images/register_logo.png", $message);
        $message = str_replace("{order_date}", $ord_datetime, $message);
        $message = str_replace("{order_id}", $ord_id, $message);
        $message = str_replace("{payment_method}", ((!empty($payment_method)) ? ucwords($payment_method) : 'Bezahlen Sie mit Rechnung'), $message);
        $message = str_replace("{additional_info}", (!empty($additional_info)) ? $additional_info : ' ', $message);
        $message = str_replace("{house_and_street}", $house_and_street, $message);
        $message = str_replace("{location_and_country}", $location_and_country, $message);
        $message = str_replace("{customer_name}", $customer_name, $message);
        //$message = str_replace("{gender}", $gender, $message);
        $message = str_replace("{order_detail}", $order_detail, $message);
        $message = str_replace("{ord_gross_total}", str_replace(".", ",", $ord_gross_total), $message);
        $message = str_replace("{ord_gst}", str_replace(".", ",", $ord_gst), $message);
        $message = str_replace("{ord_shipping_charges}", str_replace(".", ",", $ord_shipping_charges), $message);
        $message = str_replace("{ord_amount}", $ord_amount, $message);
        $message = str_replace("{url_trem_condition}", $GLOBALS['siteURL'] . "privacy", $message);
        $message = str_replace("{url_privacy_policy}", $GLOBALS['siteURL'] . "term", $message);
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        $this->order_attachment_file("webshop-edi@wacker-buerocenter.de", "Sayed Kamal Hussaini", $ord_id);
        //print($ret);
    }

    public function order_attachment_file($send_mail,$cmessage, $ord_id)
    {
        $username = "";
        $password = "";
        $subject = "Order XML File of order id: ".$ord_id."";
        $to = $send_mail;
        //$to = "ahsannawaz9777@gmail.com";
        //$to2 = "hussaini@wacker-systems.de";
        //$to = "sayedkamalhussaini6@gmail.com";
        //$to = "ahsannawaz9777@gmx.com";
        //$to = " w-test@mail.de";

        $message = "Hi " . $cmessage . ",<br>
				<br>Hello<br>
				<br><br>Message:         
				<br><br>This is an automatic generated message. Do not reply to this message.";

        //$fileUrl = $GLOBALS['siteURL'] . 'backend/manage_order_xml.php'; // Your file URL
        $fileUrl = $GLOBALS['siteURL'] . 'backend/manage_order_xml.php?ord_id='.$ord_id; // Your file URL
        //$tempFilePath = 'tempfile_' . uniqid() . '.xml'; // Temporary file to save
		$tempFilePath = 'edimail.xml'; // Temporary file to save

        // Download the file
        file_put_contents($tempFilePath, file_get_contents($fileUrl));
        //$this->sendEmail($username, $password, $to2, $subject, $message, 1, 0);
        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0, null, $tempFilePath, $ord_id);
        //print($ret);
        if (file_exists($tempFilePath)) {
            unlink($tempFilePath);
        }
    }
    public function order_cancelation($ord_id)
    {


        $username = "";
        $password = "";
        $subject = "Auftragsstornierung";
        //$to = "ahsannawaz9777@gmail.com";

        $order_detail = "";
        $ord_gross_total = 0;
        $ord_gst = 0;
        $ord_shipping_charges = 0;
        $ord_amount = 0;
        $Query = "SELECT oi.*, ord.user_id, ord.ord_datetime, ord.ord_udate, ord.ord_gross_total, ord.ord_gst, ord.ord_amount, ord.ord_shipping_charges, di.dinfo_countries_id, c.countries_name, di.dinfo_fname, di.dinfo_lname, di.dinfo_house_no, di.dinfo_street, di.dinfo_email, di.dinfo_usa_zipcode, di.dinfo_additional_info, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pg.pg_mime_source_url, pm.pm_title_de AS pm_title FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN countries AS c ON c.countries_id = di.dinfo_countries_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' AND pg.pg_mime_order = '1' LEFT OUTER JOIN payment_method AS pm ON pm.pm_id = ord.ord_payment_method WHERE ord.ord_id = '" . $ord_id . "' ORDER BY ord.ord_datetime DESC ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            while ($row = mysqli_fetch_object($rs)) {
                $to = $row->dinfo_email;
                $ord_datetime = date('d/m/Y', strtotime($row->ord_datetime));
                $ord_id = $row->ord_id;
                $payment_method = $row->pm_title;
                $additional_info = $row->dinfo_additional_info;
                $house_and_street = $row->dinfo_house_no . " " . $row->dinfo_street;
                $location_and_country = $row->dinfo_usa_zipcode . ", " . $row->countries_name;
                $customer_name = $row->dinfo_fname . " " . $row->dinfo_lname;
                $ord_gross_total = $row->ord_gross_total;
                $ord_gst = $row->ord_gst;
                $ord_shipping_charges = $row->ord_shipping_charges;
                $ord_amount = number_format(($row->ord_amount + $ord_shipping_charges), "2", ",", "");
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                $supplier_id = $row->supplier_id;
                $pro_description_short = $row->pro_description_short;
                if($row->oi_type == 2) {
                    $supplier_id = "GRATIS fur Sie!";
                    $get_image_link = $GLOBALS['siteURL'] . "files/free_product/" .returnName("fp_file", "free_product", "fp_id", $row->fp_id);
                    $pro_description_short = returnName("fp_title_de AS fp_title", "free_product", "fp_id", $row->fp_id);
                }

                $order_detail .= '<tr>
                    <td style="width: 200px; display: inline-block; margin-right: 20px;"><img src="' .$get_image_link. '" alt="" style="max-width: 100%; display: block; margin: auto; margin-bottom: 10px;"></td>
                    <td style="width: 370px; display: inline-block;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr><td colspan="3" style="font-size: 16px; color: #000; font-weight: bold; line-height: 130%;">' . $pro_description_short . '</td></tr>
                            <tr><td height="20"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Artikenummer:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px;font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . $supplier_id . '</td>
                            </tr>
                            <tr><td height="5"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Anzahl:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . $row->oi_qty . '</td>
                            </tr>
                            <tr><td height="5"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Einzelpreis:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . str_replace(".", ",", $row->oi_amount) . ' €</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td height="20"></td></tr>
                ';
            }
        } else {
            print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
        }

        $get_email_template = json_decode(get_email_template("3"));
        $message = $get_email_template[0]->eml_contents;;
        $message = str_replace("{logo}", $GLOBALS['siteURL'] . "images/register_logo.png", $message);
        $message = str_replace("{order_date}", $ord_datetime, $message);
        $message = str_replace("{order_id}", $ord_id, $message);
        $message = str_replace("{payment_method}", ((!empty($payment_method)) ? ucwords($payment_method) : 'Bezahlen Sie mit Rechnung'), $message);
        $message = str_replace("{additional_info}", (!empty($additional_info)) ? $additional_info : ' ', $message);
        $message = str_replace("{house_and_street}", $house_and_street, $message);
        $message = str_replace("{location_and_country}", $location_and_country, $message);
        $message = str_replace("{customer_name}", $customer_name, $message);
        //$message = str_replace("{gender}", $gender, $message);
        $message = str_replace("{order_detail}", $order_detail, $message);
        $message = str_replace("{ord_gross_total}", str_replace(".", ",", $ord_gross_total), $message);
        $message = str_replace("{ord_gst}", str_replace(".", ",", $ord_gst), $message);
        $message = str_replace("{ord_shipping_charges}", str_replace(".", ",", $ord_shipping_charges), $message);
        $message = str_replace("{ord_amount}", $ord_amount, $message);
        $message = str_replace("{url_trem_condition}", $GLOBALS['siteURL'] . "privacy", $message);
        $message = str_replace("{url_privacy_policy}", $GLOBALS['siteURL'] . "term", $message);
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }

    public function order_item_cancelation($oi_id)
    {


        $username = "";
        $password = "";
        //$to = "ahsannawaz9777@gmail.com";

        $dinfo_fname = "";
        $ord_id = 0;
        $ord_datetime = "";
        $ord_cancelation_datetime = "";
        $pro_title = "";
        $get_image_link = "";
        $oi_qty = 0;
        $oi_net_total = 0;
        $Query = "SELECT oi.*, ord.ord_datetime, di.dinfo_email, di.dinfo_fname, pro.pro_custom_add, pro.pro_description_short AS pro_title, pg.pg_mime_source_url FROM order_items AS oi LEFT OUTER JOIN orders AS ord ON ord.ord_id = oi.ord_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN products AS pro ON pro.pro_id = oi.pro_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = oi.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE oi.oi_id = '" . $oi_id . "' ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
                $to = $row->dinfo_email;
                $dinfo_fname = $row->dinfo_fname;
                $ord_id = $row->ord_id;
                $ord_datetime = date('d/m/Y', strtotime($row->ord_datetime));
                $ord_cancelation_datetime = formatDateGerman(date('Y-m-d', strtotime(date_time))) . ' um ' . date('H:i', strtotime(date_time));
                
                $pro_title = $row->pro_title;
                $oi_qty = $row->oi_qty;
                $oi_net_total = $row->oi_net_total;
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                if($row->pro_custom_add == 1){
                    $get_image_link = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                }
        } 
         $subject = "Bestätigung Ihrer Stornierung – Bestellung Nr. ".$ord_id;

            $message = '
                    <p>Guten Tag '.$dinfo_fname.',</p>

                    <p>
                        Sie haben Ihre Bestellung Nr. <b>'.$ord_id.'</b> vom
                        <b>'.$ord_datetime.'</b> vollständig storniert.
                    </p>

                    <p>
                        Die Bestellung ist damit abgeschlossen und wird weder weiter bearbeitet
                        noch versendet.
                    </p>

                    <p>
                        <b>Storniert am:</b> '.$ord_cancelation_datetime.'
                    </p>

                    <br>

                    <h3>Stornierte Artikel:</h3>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" valign="top">
                                <img src="'.$get_image_link.'" alt="'.$pro_title.'" width="120" style="width:120px; height:auto; display:block; border:0;">
                            </td>

                            <td valign="top" style="padding-left:15px;">
                                <p style="margin:0 0 8px 0;">
                                    <b>'.$pro_title.'</b>
                                </p>

                                <p style="margin:0;">
                                    Menge: '.$oi_qty.'
                                </p>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <p>
                        <b>
                            Gesamtbetrag der stornierten Bestellung:
                            '.$oi_net_total.' € inklusive Mehrwertsteuer
                        </b>
                    </p>

                    <br>

                    <p>
                        Da Ihre Bestellung noch nicht bestätigt wurde, wird kein Betrag endgültig
                        eingezogen. Eine eventuell vorgemerkte oder autorisierte Zahlung wird aufgehoben.
                    </p>

                    <p>
                        Falls Sie <b>Vorkasse</b> gewählt haben, überweisen Sie den Betrag bitte nicht.
                        Sollte bereits eine Zahlung veranlasst worden sein, erstatten wir den bei uns
                        eingegangenen Betrag vollständig über den ursprünglich verwendeten Zahlungsweg zurück.
                    </p>

                    <p>
                        Sie müssen nichts weiter unternehmen.
                    </p>

                    <br>

                    <p>
                        Bei Fragen zu Ihrer Stornierung steht Ihnen unser Wacker Bürocenter GmbH - Kundenservice
                        gerne zur Verfügung:
                    </p>

                    <p>
                        <b>E-Mail:</b>
                        <a href="mailto:service@wacker-buerocenter.de">
                            service@wacker-buerocenter.de
                        </a>
                        <br>

                        <b>Telefon:</b> +49 (0) 6321 9124-80
                        <br>

                        <b>Kontakt:</b>
                        <a href="' . $GLOBALS['siteURL'] . 'kontakt" target="_blank">
                            www.wacker-buerocenter.de/kontakt
                        </a>
                    </p>

                    <br>

                    <p>
                        Freundliche Grüße
                    </p>

                    <p>
                        <b>Ihr Team von Wacker Bürocenter GmbH</b><br>
                        Moderne Arbeitswelten für Büro, Homeoffice und Unternehmen
                    </p>

                    
                    ';
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }

    public function order_item_return($oi_id, $return_reason, $return_method)
    {


        $username = "";
        $password = "";
        //$to = "ahsannawaz9777@gmail.com";

        $dinfo_fname = "";
        $ord_id = 0;
        $return_request_datetime = "";
        $pro_title = "";
        $get_image_link = "";
        $oi_qty = 0;
        $Query = "SELECT oi.*, di.dinfo_email, di.dinfo_fname, pro.pro_custom_add, pro.pro_description_short AS pro_title, pg.pg_mime_source_url FROM order_items AS oi LEFT OUTER JOIN delivery_info AS di ON di.ord_id = oi.ord_id LEFT OUTER JOIN products AS pro ON pro.pro_id = oi.pro_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = oi.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE oi.oi_id = '" . $oi_id . "' ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
                $to = $row->dinfo_email;
                $dinfo_fname = $row->dinfo_fname;
                $ord_id = $row->ord_id;
                $return_request_datetime = formatDateGerman(date('Y-m-d', strtotime(date_time))) . ' um ' . date('H:i', strtotime(date_time));
                
                $pro_title = $row->pro_title;
                $oi_qty = $row->oi_qty;
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                if($row->pro_custom_add == 1){
                    $get_image_link = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                }
        } 
         $subject = "Ihre Rücksendeanfrage ist eingegangen – Bestellung Nr.  ".$ord_id;

            $message = '
                    <p>Guten Tag '.$dinfo_fname.',</p>

                    <p>
                        wir haben Ihre Rücksendeanfrage zur Bestellung Nr.
                        <b>'.$ord_id.'</b> erfolgreich erhalten.
                    </p>

                    <br>

                    <h3>Übersicht Ihrer Rücksendeanfrage:</h3>

                    <p>
                        <b>Bestellnummer:</b> '.$ord_id.'<br>
                        <b>Beantragt am:</b> '.$return_request_datetime.'<br>
                        <b>Rücksendegrund:</b> '.$return_reason.'<br>
                        <b>Rücksendeweg:</b> '.$return_method.'
                    </p>

                    <br>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" valign="top">
                                <img src="'.$get_image_link.'" alt="'.$pro_title.'" width="120" style="width:120px; height:auto; display:block; border:0;">
                            </td>

                            <td valign="top" style="padding-left:15px;">
                                <p style="margin:0 0 8px 0;">
                                    <b>'.$pro_title.'</b>
                                </p>

                                <p style="margin:0;">
                                    Rücksendemenge: '.$oi_qty.' von '.$oi_qty.'
                                </p>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <h3>So geht es weiter:</h3>

                    <p>
                        <b>1.</b>&nbsp;&nbsp;
                        Wir prüfen Ihre Angaben und bereiten die Rücksendung vor.
                    </p>

                    <p>
                        <b>2.</b>&nbsp;&nbsp;
                        Anschließend erhalten Sie eine separate E-Mail mit den
                        Rücksendeinformationen und – sofern vorgesehen – Ihrem Rücksendeetikett.
                    </p>

                    <p>
                        <b>3.</b>&nbsp;&nbsp;
                        Nach Eingang und Prüfung der Ware informieren wir Sie über den
                        Abschluss der Rücksendung sowie die weitere Bearbeitung,
                        beispielsweise eine Erstattung, Gutschrift oder Ersatzlieferung.
                    </p>

                    <br>

                    <p>
                        Bitte senden Sie den Artikel erst zurück, nachdem Sie unsere
                        Rücksendeinformationen erhalten haben. Bis dahin müssen Sie
                        nichts weiter unternehmen.
                    </p>

                    <br>

                    <p>
                        Bei Fragen zu Ihrer Rücksendeanfrage steht Ihnen unser
                        Wacker Bürocenter GmbH - Kundenservice gerne zur Verfügung:
                    </p>

                    <p>
                        <b>E-Mail:</b>
                        <a href="mailto:service@wacker-buerocenter.de">
                            service@wacker-buerocenter.de
                        </a>
                        <br>

                        <b>Telefon:</b> +49 (0) 6321 9124-80
                        <br>

                        <b>Kontakt:</b>
                        <a href="' . $GLOBALS['siteURL'] . 'kontakt" target="_blank">
                            www.wacker-buerocenter.de/kontakt
                        </a>
                    </p>

                    <br>

                    <p>
                        Freundliche Grüße
                    </p>

                    <p>
                        <b>Ihr Team von Wacker Bürocenter GmbH</b><br>
                        Moderne Arbeitswelten für Büro, Homeoffice und Unternehmen
                    </p>

                    
                ';
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }
    public function order_item_return_approvrd($orid_id)
    {
        $return_reasons = [
            1  => 'Irrtümlich bestellt',
            2  => 'Artikel wird nicht mehr benötigt',
            3  => 'Artikel ist für den vorgesehenen Einsatz ungeeignet',
            4  => 'Artikel entspricht nicht den Erwartungen',
            5  => 'Artikel ist fehlerhaft oder funktioniert nicht',
            6  => 'Artikel und Versandverpackung sind beschädigt',
            7  => 'Artikel ist beschädigt, die Versandverpackung ist jedoch unbeschädigt',
            8  => 'Falschen Artikel erhalten',
            9  => 'Teile oder Zubehör fehlen',
            10 => 'Gelieferte Menge weicht von der bestellten Menge ab',
            11 => 'Artikel entspricht nicht der Beschreibung auf der Website',
            12 => 'Artikel wurde zu spät geliefert',
            13 => 'Bestellung wurde nicht von mir oder uns aufgegeben'
        ];

        $courier_method = "<p>
                        Bitte drucken Sie den Rücknahmeschein aus und halten Sie ihn zusammen
                        mit der zurückzugebenden Ware für die Abholung durch unseren
                        WACKER-Lieferservice „ecodirect“ bereit.
                    </p>

                    <p>
                        Bei der Übernahme scannt unser Fahrer den Rücknahmeschein und bestätigt
                        damit die Abholung.
                    </p>";


        $username = "";
        $password = "";
        //$to = "ahsannawaz9777@gmail.com";

        $dinfo_fname = "";
        $ord_id = 0;
        $return_request_datetime = "";
        $pro_title = "";
        $get_image_link = "";
        $orid_qty = 0;
        $return_method = "";
        $return_reason = "";
        $Query = "SELECT orid.*, di.dinfo_email, di.dinfo_fname, pro.pro_custom_add, pro.pro_description_short AS pro_title, pg.pg_mime_source_url FROM order_return_item_detail AS orid LEFT OUTER JOIN delivery_info AS di ON di.ord_id = orid.ord_id LEFT OUTER JOIN products AS pro ON pro.pro_id = orid.pro_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = orid.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = orid.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE orid.orid_id = '" . $orid_id . "' ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
                $to = $row->dinfo_email;
                $dinfo_fname = $row->dinfo_fname;
                $ord_id = $row->ord_id;
                $return_request_datetime = formatDateGerman(date('Y-m-d', strtotime(date_time))) . ' um ' . date('H:i', strtotime(date_time));
                
                $pro_title = $row->pro_title;
                $orid_qty = $row->orid_qty;
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                if($row->pro_custom_add == 1){
                    $get_image_link = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                }

                $return_method = "ecodirect – Lieferung mit eigenem Fuhrpark";
                if($row->orid_courier_type == 2){
                    $return_method = "DHL – versicherter Versand";
                    $courier_method = '<p>
                        <a href="'.$GLOBALS['siteURL']."files/return_labels/".$row->orid_lable.'"
                        target="_blank"
                        style="
                                display:inline-block;
                                padding:12px 20px;
                                background:#000000;
                                color:#ffffff;
                                text-decoration:none;
                                font-weight:bold;
                                border-radius:4px;
                        ">
                            Rücksendedokument herunterladen
                        </a>
                    </p>

                    <br>

                    <p>
                        Bitte drucken Sie das DHL-Rücksendeetikett aus und befestigen Sie es
                        gut sichtbar auf dem Versandpaket. Entfernen oder überkleben Sie
                        vorhandene Versandetiketten und Barcodes.
                    </p>

                    <p>
                        Anschließend können Sie das Paket bei einer DHL-Annahmestelle abgeben.
                        Bitte bewahren Sie den Einlieferungsbeleg bis zum Abschluss der
                        Rücksendung auf.
                    </p>';
                }
                $return_reason = $return_reasons[$row->orid_type]; 
        } 
         $subject = "Ihre Rücksendung wurde angemeldet – Bestellung Nr.  ".$ord_id;

            $message = '
                    <p>Guten Tag '.$dinfo_fname.',</p>

                    <p>
                        wir bestätigen den Eingang Ihrer angemeldeten Rücksendung
                        zur Bestellung Nr. <b>'.$ord_id.'</b>.
                    </p>

                    <br>

                    <h3>Angaben zu Ihrer Rücksendung:</h3>

                    <p>
                        <b>Bestellnummer:</b> '.$ord_id.'<br>
                        <b>Angemeldet am:</b> '.$return_request_datetime.'<br>
                        <b>Rücksendegrund:</b> '.$return_reason.'<br>
                        <b>Rücksendeweg:</b> '.$return_method.'
                    </p>

                    <br>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" valign="top">
                                <img src="'.$get_image_link.'" alt="'.$pro_title.'" width="120" style="width:120px; height:auto; display:block; border:0;">
                            </td>

                            <td valign="top" style="padding-left:15px;">
                                <p style="margin:0 0 8px 0;">
                                    <b>'.$pro_title.'</b>
                                </p>

                                <p style="margin:0;">
                                    Rücksendemenge: '.$orid_qty.' von '.$orid_qty.'
                                </p>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <p>
                        Ihr Rücksendedokument steht ab sofort in Ihrem Kundenkonto
                        zum Herunterladen und Ausdrucken bereit:
                    </p>

                    <br>

                    '.$courier_method.'
                    <br>

                    <p>
                        Sobald die zurückgesendete Ware bei uns eingegangen und geprüft worden ist,
                        informieren wir Sie über den Abschluss der Rücksendung und die weitere
                        Bearbeitung, beispielsweise eine Erstattung, Gutschrift oder Ersatzlieferung.
                    </p>

                    <br>

                    <p>
                        Bei Fragen zu Ihrer Rücksendung steht Ihnen unser Wacker Bürocenter GmbH - Kundenservice
                        gerne zur Verfügung:
                    </p>

                    <p>
                        <b>E-Mail:</b>
                        <a href="mailto:service@wacker-buerocenter.de">
                            service@wacker-buerocenter.de
                        </a>
                        <br>

                        <b>Telefon:</b> +49 (0) 6321 9124-80
                        <br>

                        <b>Kontakt:</b>
                        <a href="' . $GLOBALS['siteURL'] . 'kontakt" target="_blank">
                            www.wacker-buerocenter.de/kontakt
                        </a>
                    </p>

                    <br>

                    <p>
                        Freundliche Grüße
                    </p>

                    <p>
                        <b>Ihr Team von Wacker Bürocenter GmbH</b><br>
                        Moderne Arbeitswelten für Büro, Homeoffice und Unternehmen
                    </p>

                    
                ';
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }
    
    public function order_item_return_canceled($orid_id, $rejection_reason_title, $rejection_reason_message)
    {
        $return_reasons = [
            1  => 'Irrtümlich bestellt',
            2  => 'Artikel wird nicht mehr benötigt',
            3  => 'Artikel ist für den vorgesehenen Einsatz ungeeignet',
            4  => 'Artikel entspricht nicht den Erwartungen',
            5  => 'Artikel ist fehlerhaft oder funktioniert nicht',
            6  => 'Artikel und Versandverpackung sind beschädigt',
            7  => 'Artikel ist beschädigt, die Versandverpackung ist jedoch unbeschädigt',
            8  => 'Falschen Artikel erhalten',
            9  => 'Teile oder Zubehör fehlen',
            10 => 'Gelieferte Menge weicht von der bestellten Menge ab',
            11 => 'Artikel entspricht nicht der Beschreibung auf der Website',
            12 => 'Artikel wurde zu spät geliefert',
            13 => 'Bestellung wurde nicht von mir oder uns aufgegeben'
        ];


        $username = "";
        $password = "";
        //$to = "ahsannawaz9777@gmail.com";

        $dinfo_fname = "";
        $ord_id = 0;
        $return_request_datetime = "";
        $pro_title = "";
        $get_image_link = "";
        $orid_qty = 0;
        $return_method = "";
        $return_reason = "";
        $Query = "SELECT orid.*, di.dinfo_email, di.dinfo_fname, pro.pro_custom_add, pro.pro_description_short AS pro_title, pg.pg_mime_source_url FROM order_return_item_detail AS orid LEFT OUTER JOIN delivery_info AS di ON di.ord_id = orid.ord_id LEFT OUTER JOIN products AS pro ON pro.pro_id = orid.pro_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = orid.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = orid.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE orid.orid_id = '" . $orid_id . "' ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
                $to = $row->dinfo_email;
                $dinfo_fname = $row->dinfo_fname;
                $ord_id = $row->ord_id;
                $return_request_datetime = formatDateGerman(date('Y-m-d', strtotime(date_time))) . ' um ' . date('H:i', strtotime(date_time));
                
                $pro_title = $row->pro_title;
                $orid_qty = $row->orid_qty;
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                if($row->pro_custom_add == 1){
                    $get_image_link = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                }

                $return_method = "ecodirect – Lieferung mit eigenem Fuhrpark";
                if($row->orid_courier_type == 2){
                    $return_method = "DHL – versicherter Versand";
                    
                }
                $return_reason = $return_reasons[$row->orid_type]; 
        } 
         $subject = "Ihre Rücksendeanfrage konnte nicht bestätigt werden – Bestellung Nr. ".$ord_id;

            $message = '
                    <p>Guten Tag '.$dinfo_fname.',</p>

                    <p>
                        wir haben Ihre Rücksendeanfrage zur Bestellung Nr.
                        <b>'.$ord_id.'</b> geprüft.
                    </p>

                    <br>

                    <h3>Angaben zu Ihrer Rücksendeanfrage:</h3>

                    <p>
                        <b>Bestellnummer:</b> '.$ord_id.'<br>
                        <b>Beantragt am:</b> '.$return_request_datetime.'<br>
                        <b>Rücksendegrund:</b> '.$return_reason.'<br>
                        <b>Gewünschter Rücksendeweg:</b> '.$return_method.'
                    </p>

                    <br>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" valign="top">
                                <img src="'.$get_image_link.'" alt="'.$pro_title.'" width="120" style="width:120px; height:auto; display:block; border:0;">
                            </td>

                            <td valign="top" style="padding-left:15px;">
                                <p style="margin:0 0 8px 0;">
                                    <b>'.$pro_title.'</b>
                                </p>

                                <p style="margin:0;">
                                    Rücksendemenge: '.$orid_qty.' von '.$orid_qty.'
                                </p>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <p>
                        Leider können wir Ihre Rücksendeanfrage aus folgendem Grund
                        nicht bestätigen:
                    </p>
                    
                    <p><b>'.$rejection_reason_title.'</b></p>

                    <p>
                        '.$rejection_reason_message.'
                    </p>

                    <p>
                        Die Rücksendeanfrage wurde damit geschlossen.
                        Ein Rücksendeetikett beziehungsweise Rücknahmeschein wird
                        nicht bereitgestellt.
                        Bitte senden Sie den Artikel nicht ohne vorherige Abstimmung
                        an uns zurück.
                    </p>

                    <p>
                        Wenn Sie Rückfragen zu unserer Entscheidung haben, ergänzende
                        Informationen einreichen möchten oder von einem Missverständnis
                        ausgehen, steht Ihnen unser Wacker Bürocenter Gmbh -Kundenservice gerne zur Verfügung.
                        Wir prüfen Ihr Anliegen bei Bedarf erneut.
                    </p>

                    <p>
                        Ihre gesetzlichen Rechte bleiben von dieser Entscheidung unberührt.
                    </p>

                    <br>

                    <p>
                        <b>E-Mail:</b>
                        <a href="mailto:service@wacker-buerocenter.de">
                            service@wacker-buerocenter.de
                        </a>
                        <br>

                        <b>Telefon:</b> +49 (0) 6321 9124-80
                        <br>

                        <b>Kontakt:</b>
                        <a href="' . $GLOBALS['siteURL'] . 'kontakt" target="_blank">
                            www.wacker-buerocenter.de/kontakt
                        </a>
                    </p>

                    <br>

                    <p>
                        Freundliche Grüße
                    </p>

                    <p>
                        <b>Ihr Team von Wacker Bürocenter GmbH</b><br>
                        Moderne Arbeitswelten für Büro, Homeoffice und Unternehmen
                    </p>

                    
                ';
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }

    public function order_item_return_received($orid_id)
    {
        $return_reasons = [
            1  => 'Irrtümlich bestellt',
            2  => 'Artikel wird nicht mehr benötigt',
            3  => 'Artikel ist für den vorgesehenen Einsatz ungeeignet',
            4  => 'Artikel entspricht nicht den Erwartungen',
            5  => 'Artikel ist fehlerhaft oder funktioniert nicht',
            6  => 'Artikel und Versandverpackung sind beschädigt',
            7  => 'Artikel ist beschädigt, die Versandverpackung ist jedoch unbeschädigt',
            8  => 'Falschen Artikel erhalten',
            9  => 'Teile oder Zubehör fehlen',
            10 => 'Gelieferte Menge weicht von der bestellten Menge ab',
            11 => 'Artikel entspricht nicht der Beschreibung auf der Website',
            12 => 'Artikel wurde zu spät geliefert',
            13 => 'Bestellung wurde nicht von mir oder uns aufgegeben'
        ];


        $username = "";
        $password = "";
        //$to = "ahsannawaz9777@gmail.com";

        $dinfo_fname = "";
        $ord_id = 0;
        $return_received_datetime = "";
        $pro_title = "";
        $get_image_link = "";
        $orid_qty = 0;
        $return_reason = "";
        $Query = "SELECT orid.*, di.dinfo_email, di.dinfo_fname, pro.pro_custom_add, pro.pro_description_short AS pro_title, pg.pg_mime_source_url FROM order_return_item_detail AS orid LEFT OUTER JOIN delivery_info AS di ON di.ord_id = orid.ord_id LEFT OUTER JOIN products AS pro ON pro.pro_id = orid.pro_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = orid.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = orid.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE orid.orid_id = '" . $orid_id . "' ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
                $to = $row->dinfo_email;
                $dinfo_fname = $row->dinfo_fname;
                $ord_id = $row->ord_id;
                $return_received_datetime = formatDateGerman(date('Y-m-d', strtotime(date_time))) . ' um ' . date('H:i', strtotime(date_time));
                
                $pro_title = $row->pro_title;
                $orid_qty = $row->orid_qty;
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                if($row->pro_custom_add == 1){
                    $get_image_link = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                }

                $return_reason = $return_reasons[$row->orid_type]; 
        } 
         $subject = "Ihre Rücksendung ist bei uns eingegangen – Bestellung Nr. ".$ord_id;

            $message = '
                    <p>Guten Tag '.$dinfo_fname.',</p>

                    <p>
                        Ihre Rücksendung zur Bestellung Nr. <b>'.$ord_id.'</b>
                        ist am <b>'.$return_received_datetime.'</b> bei uns eingegangen.
                    </p>

                    <br>

                    <h3>Angaben zu Ihrer Rücksendung:</h3>

                    <p>
                        <b>Bestellnummer:</b> '.$ord_id.'<br>
                        <b>Rücksendenummer:</b> '.$orid_id.'<br>
                        <b>Rücksendegrund:</b> '.$return_reason.'<br>
                        <b>Eingegangen am:</b> '.$return_received_datetime.'
                    </p>

                    <br>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" valign="top">
                                <img src="'.$get_image_link.'" alt="'.$pro_title.'" width="120" style="width:120px; height:auto; display:block; border:0;">
                            </td>

                            <td valign="top" style="padding-left:15px;">
                                <p style="margin:0 0 8px 0;">
                                    <b>'.$pro_title.'</b>
                                </p>

                                <p style="margin:0;">
                                    Rücksendemenge: '.$orid_qty.' von '.$orid_qty.'
                                </p>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <p>
                        Ihre Rücksendung wird nun von uns geprüft. Dabei kontrollieren wir
                        insbesondere den Zustand, die Vollständigkeit und die zurückgesendete
                        Menge des Artikels.
                    </p>

                    <p>
                        <b>Bitte beachten Sie:</b> Die Bestätigung des Wareneingangs stellt
                        noch keine endgültige Annahme der Rücknahme dar. Nach Abschluss der
                        Prüfung informieren wir Sie per E-Mail über das Ergebnis und die
                        weitere Bearbeitung.
                    </p>

                    <p>
                        Sofern die Rücknahme vollständig oder teilweise akzeptiert wird,
                        erhalten Sie anschließend weitere Informationen zur Gutschrift,
                        Erstattung oder gegebenenfalls zur Ersatzlieferung.
                    </p>

                    <br>

                    <p>
                        Bei Fragen zu Ihrer Rücksendung steht Ihnen unser
                        Wacker Bürocenter GmbH - Kundenservice gerne zur Verfügung.
                    </p>

                    <p>
                        Bitte geben Sie dabei die Bestell- oder Rücksendenummer an.
                    </p>

                    <p>
                        <b>E-Mail:</b>
                        <a href="mailto:service@wacker-buerocenter.de">
                            service@wacker-buerocenter.de
                        </a>
                        <br>

                        <b>Telefon:</b>
                        <a href="tel:+496321912480">
                            +49 (0) 6321 9124-80
                        </a>
                        <br>

                        <b>Kontakt:</b>
                        <a href="' . $GLOBALS['siteURL'] . 'kontakt" target="_blank">
                            www.wacker-buerocenter.de/kontakt
                        </a>
                    </p>

                    <br>

                    <p>
                        Freundliche Grüße
                    </p>

                    <p>
                        <b>Ihr Team von Wacker Bürocenter GmbH</b><br>
                        Moderne Arbeitswelten für Büro, Homeoffice und Unternehmen
                    </p>

                    
                ';
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }

    public function order_item_return_completed($orid_id)
    {
        
        $username = "";
        $password = "";
        //$to = "ahsannawaz9777@gmail.com";

        $dinfo_fname = "";
        $ord_id = 0;
        $return_completed_datetime = "";
        $pro_title = "";
        $get_image_link = "";
        $orid_qty = 0;
        $credit_amount = 0;
        $Query = "SELECT orid.*, di.dinfo_email, di.dinfo_fname, pro.pro_custom_add, pro.pro_description_short AS pro_title, pg.pg_mime_source_url FROM order_return_item_detail AS orid LEFT OUTER JOIN delivery_info AS di ON di.ord_id = orid.ord_id LEFT OUTER JOIN products AS pro ON pro.pro_id = orid.pro_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = orid.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = orid.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE orid.orid_id = '" . $orid_id . "' ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
                $to = $row->dinfo_email;
                $dinfo_fname = $row->dinfo_fname;
                $ord_id = $row->ord_id;
                $return_completed_datetime = formatDateGerman(date('Y-m-d', strtotime(date_time))) . ' um ' . date('H:i', strtotime(date_time));
                
                $pro_title = $row->pro_title;
                $orid_qty = $row->orid_qty;
                $credit_amount = price_format($row->orid_net_total);
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                if($row->pro_custom_add == 1){
                    $get_image_link = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                } 
        } 
         $subject = "Ihre Rücksendung wurde abgeschlossen – Bestellung Nr. ".$ord_id;

            $message = '
                    <p>Guten Tag '.$dinfo_fname.',</p>

                    <p>
                        die Prüfung und Bearbeitung Ihrer Rücksendung zur Bestellung Nr.
                        <b>'.$ord_id.'</b> ist abgeschlossen.
                    </p>

                    <p>
                        Die von Ihnen zurückgesendeten Artikel und Mengen wurden vollständig
                        zur Rücknahme angenommen.
                    </p>

                    <br>

                    <h3>Angaben zum abgeschlossenen Rücksendevorgang:</h3>

                    <p>
                        <b>Bestellnummer:</b> '.$ord_id.'<br>
                        <b>Rücksendenummer:</b> '.$orid_id.'<br>
                        <b>Gutschriftsbetrag:</b> '.$credit_amount.' €
                    </p>

                    <br>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" valign="top">
                                <img src="'.$get_image_link.'" alt="'.$pro_title.'" width="120" style="width:120px; height:auto; display:block; border:0;">
                            </td>

                            <td valign="top" style="padding-left:15px;">
                                <p style="margin:0 0 8px 0;">
                                    <b>'.$pro_title.'</b>
                                </p>

                                <p style="margin:0;">
                                    Rücksendemenge: '.$orid_qty.' von '.$orid_qty.'
                                </p>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <p>
                        <b>Art der Abwicklung:</b> Erstattung über die ursprüngliche Zahlungsart/Ersatzlieferung<br>
                        <b>Abgeschlossen am:</b> '.$return_completed_datetime.'
                    </p>

                    <br>

                    <p>
                        Die Gutschrift wurde erstellt und die oben genannte weitere Abwicklung
                        von uns veranlasst.
                    </p>

                    <p>
                        Sofern eine Erstattung erfolgt, wurde diese über die ursprünglich
                        verwendete Zahlungsart veranlasst. Abhängig vom Zahlungsdienstleister
                        oder Kreditinstitut kann es einige Werktage dauern, bis der Betrag
                        für Sie sichtbar ist.
                    </p>

                    <p>
                        Sofern eine Ersatzlieferung erfolgt, erhalten Sie die
                        Versandinformationen mit einer separaten Versandbestätigung.
                    </p>

                    <p>
                        Sie müssen nichts weiter unternehmen.
                    </p>

                    <br>

                    <p>
                        Bei Fragen zu Ihrer Rücksendung steht Ihnen unser Wacker Bürocenter GmbH - Kundenservice
                        gerne zur Verfügung. Bitte geben Sie dabei die Bestell- oder
                        Rücksendenummer an.
                    </p>

                    <p>
                        <b>E-Mail:</b>
                        <a href="mailto:service@wacker-buerocenter.de">
                            service@wacker-buerocenter.de
                        </a>
                        <br>

                        <b>Telefon:</b>
                        <a href="tel:+496321912480">
                            +49 (0) 6321 9124-80
                        </a>
                        <br>

                        <b>Kontakt:</b>
                        <a href="' . $GLOBALS['siteURL'] . 'kontakt" target="_blank">
                            www.wacker-buerocenter.de/kontakt
                        </a>
                    </p>

                    <br>

                    <p>
                        Freundliche Grüße
                    </p>

                    <p>
                        <b>Ihr Team von Wacker Bürocenter GmbH</b><br>
                        Moderne Arbeitswelten für Büro, Homeoffice und Unternehmen
                    </p>

                    
                ';
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }

    public function order_item_return_rejected_after_inspection($orid_id, $rejection_reason_title, $rejection_reason_message)
    {
        $return_reasons = [
            1  => 'Irrtümlich bestellt',
            2  => 'Artikel wird nicht mehr benötigt',
            3  => 'Artikel ist für den vorgesehenen Einsatz ungeeignet',
            4  => 'Artikel entspricht nicht den Erwartungen',
            5  => 'Artikel ist fehlerhaft oder funktioniert nicht',
            6  => 'Artikel und Versandverpackung sind beschädigt',
            7  => 'Artikel ist beschädigt, die Versandverpackung ist jedoch unbeschädigt',
            8  => 'Falschen Artikel erhalten',
            9  => 'Teile oder Zubehör fehlen',
            10 => 'Gelieferte Menge weicht von der bestellten Menge ab',
            11 => 'Artikel entspricht nicht der Beschreibung auf der Website',
            12 => 'Artikel wurde zu spät geliefert',
            13 => 'Bestellung wurde nicht von mir oder uns aufgegeben'
        ];


        $username = "";
        $password = "";
        //$to = "ahsannawaz9777@gmail.com";

        $dinfo_fname = "";
        $ord_id = 0;
        $return_request_reject_datetime = "";
        $pro_title = "";
        $get_image_link = "";
        $orid_qty = 0;
        $return_reason = "";
        $Query = "SELECT orid.*, di.dinfo_email, di.dinfo_fname, pro.pro_custom_add, pro.pro_description_short AS pro_title, pg.pg_mime_source_url FROM order_return_item_detail AS orid LEFT OUTER JOIN delivery_info AS di ON di.ord_id = orid.ord_id LEFT OUTER JOIN products AS pro ON pro.pro_id = orid.pro_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = orid.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = orid.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE orid.orid_id = '" . $orid_id . "' ";
        //print($Query);die();          
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
                $to = $row->dinfo_email;
                $dinfo_fname = $row->dinfo_fname;
                $ord_id = $row->ord_id;
                $return_request_reject_datetime = formatDateGerman(date('Y-m-d', strtotime(date_time))) . ' um ' . date('H:i', strtotime(date_time));
                
                $pro_title = $row->pro_title;
                $orid_qty = $row->orid_qty;
                $get_image_link = get_image_link(160, $row->pg_mime_source_url);
                if($row->pro_custom_add == 1){
                    $get_image_link = $GLOBALS['siteURL'].$row->pg_mime_source_url;
                }

                $return_reason = $return_reasons[$row->orid_type]; 
        } 
         $subject = "Ergebnis der Prüfung Ihrer Rücksendung – Bestellung Nr. ".$ord_id;

            $message = '
                    <p>Guten Tag '.$dinfo_fname.',</p>

                    <p>
                        wir haben die Prüfung Ihrer Rücksendung zur Bestellung Nr.
                        <b>'.$ord_id.'</b> abgeschlossen.
                    </p>

                    <p>
                        Leider können wir die zurückgesendeten Artikel beziehungsweise Mengen
                        aufgrund des Prüfergebnisses nicht zur Rücknahme annehmen.
                    </p>

                    <br>

                    <h3>Angaben zu Ihrer Rücksendung:</h3>

                    <p>
                        <b>Bestellnummer:</b> '.$ord_id.'<br>
                        <b>Rücksendenummer:</b> '.$orid_id.'<br>
                        <b>Rücksendegrund:</b> '.$return_reason.'<br>
                        <b>Prüfung abgeschlossen am:</b> '.$return_request_reject_datetime.'
                    </p>

                    <br>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="130" valign="top">
                                <img src="'.$get_image_link.'" alt="'.$pro_title.'" width="120" style="width:120px; height:auto; display:block; border:0;">
                            </td>

                            <td valign="top" style="padding-left:15px;">
                                <p style="margin:0 0 8px 0;">
                                    <b>'.$pro_title.'</b>
                                </p>

                                <p style="margin:0;">
                                    Rücksendemenge: '.$orid_qty.' von '.$orid_qty.'
                                </p>
                            </td>
                        </tr>
                    </table>

                    <br>

                    <h3>'.$rejection_reason_title.':</h3>

                    <p>
                        '.$rejection_reason_message.'
                    </p>

                    <p>
                        Aufgrund dieses Prüfergebnisses können wir für die nicht zur Rücknahme
                        angenommenen Artikel keine Gutschrift, Erstattung oder Ersatzlieferung
                        veranlassen.
                    </p>

                    <p>
                        Die Artikel werden kostenfrei an die bei Ihrer Bestellung hinterlegte
                        Lieferadresse zurückgesendet. Sobald der Rückversand erfolgt ist,
                        erhalten Sie eine separate Versandbestätigung mit den verfügbaren
                        Sendungsinformationen. Sie müssen derzeit nichts weiter unternehmen.
                    </p>

                    <p>
                        Wenn Sie Rückfragen zum Prüfergebnis haben, ergänzende Informationen
                        einreichen möchten oder von einem Missverständnis ausgehen, steht Ihnen
                        unser Wacker Bürocenter GmbH - Kundenservice gerne zur Verfügung. Wir prüfen Ihr Anliegen
                        bei Bedarf erneut.
                    </p>

                    <p>
                        Ihre gesetzlichen Rechte bleiben von dieser Entscheidung unberührt.
                    </p>

                    <br>

                    <p>
                        <b>E-Mail:</b>
                        <a href="mailto:service@wacker-buerocenter.de">
                            service@wacker-buerocenter.de
                        </a>
                        <br>

                        <b>Telefon:</b>
                        <a href="tel:+496321912480">
                            +49 (0) 6321 9124-80
                        </a>
                        <br>

                        <b>Kontakt:</b>
                        <a href="' . $GLOBALS['siteURL'] . 'kontakt" target="_blank">
                            www.wacker-buerocenter.de/kontakt
                        </a>
                    </p>

                    <br>

                    <p>
                        Freundliche Grüße
                    </p>

                    <p>
                        <b>Ihr Team von Wacker Bürocenter GmbH</b><br>
                        Moderne Arbeitswelten für Büro, Homeoffice und Unternehmen
                    </p>

                    
                ';
        //print_r($message);die();

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }



    public function vorkasse($email, $ord_id, $customer_name, $order_net_amount)
    {
        $username = "";
        $password = "";
        $subject = "Ihre Bestellung bei Wacker Bürocenter – Zahlungsinformationen zur Vorkasse";
        //$to = "ahsannawaz9777@gmail.com";
        $to = $email;

        $message = 'Sehr geehrter, '.$customer_name.'<br>
				<br><br>vielen Dank für Ihre Bestellung in unserem Webshop!         
				<br>Sie haben sich für die Zahlungsart <b>Vorkasse</b> entschieden.
				<br><br>Bitte überweisen Sie den Gesamtbetrag Ihrer Bestellung <b>innerhalb von 7 Tagen</b> auf folgendes Konto:
				<br><br><b>Wacker Bürocenter GmbH</b>
				<br>Bank: <b>VR-Bank Südpfalz</b>
				<br>IBAN: <b>DE95 5486 2500 0006 7025 70</b>
				<br>BIC: <b>GENODE61SUW</b>
				<br><br>Bestellung: '.$ord_id.'
				<br><br>ZU ZAHLENDER BETRAG: '.price_format($order_net_amount).' €
				<br><br>Nach Zahlungseingang erhalten Sie eine Bestätigung per E-Mail. Anschließend wird Ihre Bestellung schnellstmöglich versendet.
				<br><br><b><img style="width: 20px;" data-emoji="📞" class="an1" alt="📞" aria-label="📞" draggable="false" src="'.$GLOBALS['siteURL'].'images/phone.png" loading="lazy" data-emailtracker-detector="1"> Bei Fragen sind wir gerne für Sie da:</b>
                <br><br>Hotline: <b>06321 9124-80</b>
                <br>E-Mail:bestellung@wacker-buerocenter.de <a href="mailto:'.$GLOBALS['vorkasse_email'].'" style="color:rgb(70,120,134)" target="_blank"><b>'.$GLOBALS['vorkasse_email'].'</b></a>
				<br><br>Vielen Dank für Ihr Vertrauen!
				<br><br>Mit freundlichen Grüßen
				<br><br>Ihr Team vom <b>Wacker Bürocenter</b>';


        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }
    
    public function forgotpassword($email, $token)
    {
        $username = "";
        $password = "";
        $subject = "Zurücksetzen Ihres Passworts";
        //$to = "ahsannawaz9777@gmail.com";
        $to = $email;

        $url = "wacker24.de/";
        $link = $url . "reset_password.php?token=" . $token . "";
        $message = "Hallo! <br>
				<br><br>Message:         
				<br>Bitte klicken Sie auf den folgenden Link zum Zurücksetzen Ihres Passworts:
				<br><a href='https://" . $link . "'>https://" . $link . "</a>";


        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //print($ret);
    }

    public function appointment_email_user($app_gender, $app_email, $app_lname, $app_date, $app_time, $username = "", $password = "")
    {
        $subject = "Ihr Termin ist bereits gebucht";
        $to = $app_email;

        $message = "<html>
                        <head>
                            <title>Bestätigung Ihres Termins</title>
                        </head>
                        <body>
                            <p>Sehr geehrter " . $app_gender . ' ' . $app_lname . "</p>
                            <p>wir freuen uns, Ihnen mitteilen zu können, dass Ihr Termin erfolgreich gebucht wurde.</p>
                            <p><strong>Termin Details:</strong></p>
                            <ul>
                                <li><strong>Datum:</strong> " . $app_date . "</li>
                                <li><strong>Uhrzeit:</strong> " . $app_time . "</li>
                                <li><strong>Ort:</strong> Unsere Büroadresse, Stadt, Land</li>
                            </ul>
                            <p>Bitte markieren Sie Ihren Kalender entsprechend.</p>
                            <p>Wenn Sie Fragen haben oder den Termin verschieben müssen, kontaktieren Sie uns bitte so schnell wie möglich.</p>
                            <p>Vielen Dank, dass Sie sich für unsere Dienste entschieden haben.</p>
                            <p>Mit freundlichen Grüßen,<br>Ihr Dienstanbieter</p>
                        </body>
                    </html>";

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //return $ret;
        /*print mail($To, $subject, $message, $headers);
        die;*/
    }
    public function appointment_email_admin($as_title, $app_gender, $app_email, $app_lname, $app_date, $app_time, $username = "", $password = "")
    {
        $subject = "Benachrichtigung über eine neue Terminbuchung";
        //$to = "hussaini@wacker-systems.de";
        $to = "t.wacker@wacker-buerocenter.de";

        $message = "<html>
                        <head>
                            <title>Benachrichtigung über eine neue Terminbuchung</title>
                        </head>
                        <body>
                            <p>Hello Admin,</p>
                            <p>Es wurde ein neuer Termin vereinbart. Unten sind die Details:</p>
                            <ul>
                                <li><strong>Event:</strong> " . $as_title . "</li>
                                <li><strong>Name:</strong> " . $app_gender . ' ' . $app_lname . "</li>
                                <li><strong>E-mail:</strong> " . $app_email . "</li>
                                <li><strong>Datum:</strong> " . $app_date . "</li>
                                <li><strong>Uhrzeit:</strong> " . $app_time . "</li>
                            </ul>
                            <p>Bitte ergreifen Sie die erforderlichen Maßnahmen.</p>
                            <p>Mit freundlichen Grüßen,<br>Ihr Website-Shop</p>
                        </body>
                    </html>";

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //return $ret;
        /*print mail($To, $subject, $message, $headers);
        die;*/
    }
    public function forgotPass($name, $user_name, $user_passwordd)
    {
        $username = "";
        $password = "";
        $subject = "Passwort vergessen";
        $to = $user_name;
        //$to = "aqeelashraf@gmail.com";

        $message = "Hi " . $name . ",
        <br><br><b>Ihre Anfrage für ein neues Passwort wurde erfolgreich verarbeitet.</b>
        <br>Bitte prüfen Sie die folgenden Informationen und melden Sie sich anschließend mit Ihrem neuen Passwort an.
        <br><br>Name: " . $name . "
        <br>Email: " . $user_name . "
        <br>Subject: " . $subject . "
        <br>Neues Passwort: " . $user_passwordd . "
        <br><br>Dies ist eine automatisch generierte Nachricht. Bitte antworten Sie nicht auf diese E-Mail.";

        $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        //return $ret;
        /*print mail($To, $subject, $message, $headers);
        die;*/
    }







    public function sendEmail($username, $password, $to, $subject, $message, $sendToCC = 0, $sendToBcc = 0, $bccEmail = '', $attachmentPath = null, $ord_id = 0)
    {
        $dir = '';
        $str = '';
        if ((strpos($_SERVER['SCRIPT_NAME'], '/backend/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/dashboard/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/cron/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/api/') !== false)) {
            $dir = '../';
        }

        if (!empty($username) && !empty($password)) {
            $mail_username = $username;
            $mail_password = $password;
        } else {
            /*$mail_username = "noreply@wackersystems.com";
            $mail_password = "A^tXZxQCCDM4";*/
            $mail_username = "noreply@wacker-buerocenter.de";
            $mail_password = "86@TS-AXn}7J";
        }
        //require_once($dir . "lib/class.phpmailer.php");

        //Load Composer's autoloader
        require $dir . 'vendor/autoload.php';
        try {
            // print('<br> STep 4');
            $mail = new PHPMailer(true);
            $body             = $message;
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            //$mail->Port       = 587;
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];
            //$mail->SMTPSecure = 'tls';
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //$mail->Host       = "mail.gmx.com";
            $mail->Host       = "mail.wacker-buerocenter.de";
            //$mail->Host       = "wackersystems.com";
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;

            $mail->CharSet = "UTF-8";
            $mail->Priority = 1;
            $mail->From       = $mail_username;
            $mail->FromName   = "Wacker Bürocenter";
            //$mail->AddReplyTo('wackersystems@wackersystems.com', 'Wacker24');
            if ($sendToCC == 1) {
                //$mail->AddCC('wackersystems@wackersystems.com', 'Wacker24');
            }
            if ($sendToBcc == 1) {
                $mail->AddBCC($bccEmail, 'Wacker Systems');
            }

            $mail->AddAddress($to);
            //$mail->addReplyTo('info@wackersystems.com');
            $mail->Subject    = $subject;
            $mail->AltBody    = $message;
            $mail->WordWrap   = 80;
            $mail->MsgHTML($message);
            $mail->IsHTML(true);

            // Attach file if provided
            if ($attachmentPath !== null && file_exists($attachmentPath)) {
                $mail->addAttachment($attachmentPath, 'order_'.$ord_id.'.xml'); // You can rename here
            }
            if (!$mail->send()) {
                $str = "Mailer Error: " . $mail->ErrorInfo;
            } else {
                $str = "Message has been sent successfully";
            }
        } catch (Exception $e) { //phpmailerException
            //$str = 'Mailer Exception';
            $str = $e->getMessage();
        }
        //echo $str; die;
        return $str;
    }
}
