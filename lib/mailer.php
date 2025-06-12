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
                //echo $row->pro_image;die();

                $order_detail .= '<tr>
                    <td style="width: 200px; display: inline-block; margin-right: 20px;"><img src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt="" style="max-width: 100%; display: block; margin: auto; margin-bottom: 10px;"></td>
                    <td style="width: 370px; display: inline-block;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr><td colspan="3" style="font-size: 16px; color: #000; font-weight: bold; line-height: 130%;">' . $row->pro_description_short . '</td></tr>
                            <tr><td height="20"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Artikenummer:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px;font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . $row->supplier_id . '</td>
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
        $this->order_attachment_file("webshop-edi@wacker24.de", "Sayed Kamal Hussaini", $ord_id);
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
        $tempFilePath = 'tempfile_' . uniqid() . '.xml'; // Temporary file to save

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
                //echo $row->pro_image;die();

                $order_detail .= '<tr>
                    <td style="width: 200px; display: inline-block; margin-right: 20px;"><img src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt="" style="max-width: 100%; display: block; margin: auto; margin-bottom: 10px;"></td>
                    <td style="width: 370px; display: inline-block;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr><td colspan="3" style="font-size: 16px; color: #000; font-weight: bold; line-height: 130%;">' . $row->pro_description_short . '</td></tr>
                            <tr><td height="20"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Artikenummer:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px;font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">' . $row->supplier_id . '</td>
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
        $subject = "Forget Password Request";
        $to = $user_name;
        //$to = "aqeelashraf@gmail.com";

        $message = "Hi " . $name . ",
        <br><br>Your new Password Request has been received, please see the details and login with new password:
        <br><br>Name: " . $name . "
        <br>Email: " . $user_name . "
        <br>Subject: " . $subject . "
        <br>New Password: " . $user_passwordd . "
        <br><br>This is an automatic generated message. Do not reply to this message.";

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
            $mail_username = "noreply@wacker24.de";
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
            $mail->Host       = "mail.wacker24.de";
            //$mail->Host       = "wackersystems.com";
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;

            $mail->CharSet = "UTF-8";
            $mail->Priority = 1;
            $mail->From       = $mail_username;
            $mail->FromName   = "Wacker24";
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
