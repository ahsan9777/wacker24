<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class Mailer {

    public function test($cmessage){
        $username = "";
        $password = "";
            $subject = "Mail Test";
            $to = "ahsannawaz9777@gmail.com";
            //$to = "sayedkamalhussaini6@gmail.com";
            //$to = "ahsannawaz9777@gmx.com";
            //$to = " w-test@mail.de";

            $message = "Hi ".$cmessage.",<br>
				<br>Hello<br>
				<br><br>Message:         
				<br><br>This is an automatic generated message. Do not reply to this message.";


                $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
            //print($ret);
    }
    
    function  registration_account_verification($customer_name, $username, $password, $to, $subject_title, $user_verification_code){

        //$get_email_template = json_encode();
        $get_email_template = json_decode(get_email_template("1"));
        // print("<pre>");
        // print_r($get_email_template);
        // print("</pre>");
        $subject = $get_email_template[0]->eml_subject;
        $subject = str_replace("{subject}", $subject_title, $subject);
        
        $eml_contents = $get_email_template[0]->eml_contents;
        $eml_contents = str_replace("{customer_name}", $customer_name, $eml_contents);
        $eml_contents = str_replace("{sitelogo}", $GLOBALS['siteURL']."images/register_logo.png", $eml_contents);
        $eml_contents = str_replace("{btnlink}", $GLOBALS['siteURL']."account_verification.php?verification_code=".$user_verification_code, $eml_contents);
        $eml_contents = str_replace("{href_site}", $GLOBALS['siteURL'], $eml_contents);
        $eml_contents = str_replace("{siteName}", $GLOBALS['siteName'], $eml_contents);
        $message = $eml_contents;
        //print($message); die();

       return $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
        /* print mail($To, $subject, $message, $headers);
         die;*/
    }

    public function order($order_id){

        include("../backend/conn.php");

        $username = "";
        $password = "";
            $subject = "Bestellbestätigung";
            //$to = "ahsannawaz9777@gmail.com";

            $order_detail = "";
            $product_price = 0;
            $order_subtotal = 0;
            $order_vat = 0;
            $order_shipping_charges = 0;
            $order_amount = 0;
            $Query = "SELECT uo.*, p.supplier_aid AS article_number, ( SELECT CONCAT('https://wackersystems.com/assets/Mediendaten/Bilddaten_Lager_2000_Pixel/', pi.ImageName, '.jpg') FROM product_images AS pi WHERE pi.ArticleNumberSOE = p.supplier_aid AND pi.MainImage = '1' LIMIT 0,1 ) AS pro_image, wu.email, om.additional_info, CONCAT(om.house, ' ', om.street) AS house_and_street, CONCAT(om.location, ' ', om.country) AS location_and_country, wu.lname AS name, wu.gander, om.payment_method  FROM `users_orders` AS uo LEFT OUTER JOIN product AS p ON p.id = uo.product_id  LEFT OUTER JOIN wacker_users AS wu ON wu.id = uo.user_id LEFT OUTER JOIN order_manager AS om ON om.order_id = uo.order_id  WHERE uo.order_id = '".$order_id."'";  
            //print($Query);die();          
            $rs = mysqli_query($mysqli, $Query);
            if(mysqli_num_rows($rs) > 0){
                while($row = mysqli_fetch_object($rs)){
                    $to = $row->email;
                    $order_date = date('D F j, Y', strtotime($row->order_date));
                    $order_id = $row->order_id;
                    $payment_method = $row->payment_method;
                    $additional_info = $row->additional_info;
                    $house_and_street = $row->house_and_street;
                    $location_and_country = $row->location_and_country;
                    $customer_name = $row->name;
                    $gender = $row->gander;
                    $order_subtotal = $row->order_subtotal;
                    
                    $order_vat = $row->order_vat;
                    $order_shipping_charges = $row->order_shipping_charges;
                    $order_amount = $row->order_amount;
                    $product_price = $row->product_price * $row->qty;
                    //echo $row->pro_image;die();

                    $order_detail .= '<tr>
                    <td style="width: 200px; display: inline-block; margin-right: 20px;"><img src="'.$row->pro_image.'" alt="" style="max-width: 100%; display: block; margin: auto; margin-bottom: 10px;"></td>
                    <td style="width: 370px; display: inline-block;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr><td colspan="3" style="font-size: 16px; color: #000; font-weight: bold; line-height: 130%;">'.$row->item_name.'</td></tr>
                            <tr><td height="20"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Artikenummer:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px;font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">'.$row->article_number.'</td>
                            </tr>
                            <tr><td height="5"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Anzahl:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">'.$row->qty.'</td>
                            </tr>
                            <tr><td height="5"></td></tr>
                            <tr>
                                <td style="width: 120px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">Einzelpreis:</td>
                                <td style="width: 10px;"></td>
                                <td style="width: 170px; font-size: 14px; color: #000; line-height: 130%; vertical-align: text-top;">'.number_format($product_price, "2", ",", "").' €</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td height="20"></td></tr>
                ';
                }
            } else{
                print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
            }
            //
            $message = file_get_contents('https://wacker24.de/lib/template/order/order_email.html');
            $message = str_replace("{order_date}", $order_date, $message);
            $message = str_replace("{order_id}", $order_id, $message);
            $message = str_replace("{payment_method}", ((!empty($payment_method))?ucwords($payment_method):'Bezahlen Sie mit Rechnung'), $message);
            $message = str_replace("{additional_info}", $additional_info, $message);
            $message = str_replace("{house_and_street}", $house_and_street, $message);
            $message = str_replace("{location_and_country}", $location_and_country, $message);
            $message = str_replace("{customer_name}", $customer_name, $message);
            $message = str_replace("{gender}", $gender, $message);
            $message = str_replace("{order_detail}", $order_detail, $message);
            $message = str_replace("{order_subtotal}", str_replace(".", ",", $order_subtotal), $message);
            $message = str_replace("{order_vat}", str_replace(".", ",", $order_vat), $message);
            $message = str_replace("{order_shipping_charges}", str_replace(".", ",", $order_shipping_charges), $message);
            $message = str_replace("{order_amount}", str_replace(".", ",", $order_amount), $message);
            //print_r($message);die();

                $this->sendEmail($username, $password, $to, $subject, $message, 1, 0);
            //print($ret);
    }
    

    
    public function forgotpassword($email, $token){
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
    

    

    
    

    public function sendEmail($username, $password, $to, $subject, $message, $sendToCC = 0, $sendToBcc = 0, $bccEmail = ''){
        $dir = '';
        $str = '';
        if ((strpos($_SERVER['SCRIPT_NAME'], '/backend/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/dashboard/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/cron/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/api/') !== false) ) {
            $dir = '../';
        }

        if(!empty($username) && !empty($password)){
            $mail_username = $username;
            $mail_password = $password;
        } else {
            $mail_username = "noreply@wackersystems.com";
            $mail_password = "A^tXZxQCCDM4";
        }
        //require_once($dir . "lib/class.phpmailer.php");

        //Load Composer's autoloader
        require $dir.'vendor/autoload.php';
        try {
           // print('<br> STep 4');
            $mail = new PHPMailer(true);
            $body             = $message;
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            //$mail->Port       = 587;
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            //$mail->SMTPSecure = 'tls';
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //$mail->Host       = "mail.gmx.com";
            $mail->Host       = "wackersystems.com";
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;

            $mail->CharSet = "UTF-8";
            $mail->Priority = 1;
            $mail->From       = $mail_username;
            $mail->FromName   = "Wacker24";
            //$mail->AddReplyTo('wackersystems@wackersystems.com', 'Wacker24');
            if($sendToCC == 1){
                //$mail->AddCC('wackersystems@wackersystems.com', 'Wacker24');
            }
            if($sendToBcc == 1){
                $mail->AddBCC($bccEmail, 'Wacker Systems');
            }

            $mail->AddAddress($to);
            //$mail->addReplyTo('info@wackersystems.com');
            $mail->Subject    = $subject;
            $mail->AltBody    = $message;
            $mail->WordWrap   = 80;
            $mail->MsgHTML($message);
            $mail->IsHTML(true);
            if(!$mail->send()) {
                $str = "Mailer Error: " . $mail->ErrorInfo;
            } 
            else {
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