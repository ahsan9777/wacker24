<?php
define("SITE_URL", "http://www.excellentpp.com.pk/");
function SendContact($cName, $cEmail, $cSubject, $cMessage){
 //sending email for Registration confirmation...
 $fromMail = "noreply@excellentpp.com";
 $subject = "Contact Request";
 $To = "info@excellentpp.com";
 
 $welcome = "Hi Admin,
 \n\nA new contact request has been submitted, please see the details below:

 \n\nName: ".$cName."
 \nEmail: ".$cEmail."
 \nSubject: ".$cSubject."
 \nMessage: ".$cMessage."
 
 \n\nThis is an automatic generated message. Do not reply to this message.";
 
 $message = $welcome;
    
 $headers = "From: Excellent Printing Press <" . $fromMail . ">";  

 return @mail($To, $subject, $message, $headers);
}

function sendNL($email, $Name, $subject, $emailContents){
    $mTo="info@kaamkaaj.com.pk";
    $site_url = SITE_URL;
	$emailContents = "";
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{USER_NAME}', $Name, $emailContents);
	
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $fromMail = "noreply@kaamkaaj.com.pk";
    //$fromMail = "aqeelashraf@gmail.com";
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
}

function Contact_us_to_admin($email, $Name, $subject,$message, $phone){
    $mTo="info@kaamkaaj.com.pk";
    $site_url = SITE_URL;
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=1");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$cntForm = 'Name: '.$Name.'<br>Email: '.$email.'<br>Phone: '.$phone.'<br>'.$message;
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{CONTACT_DETAILS}', $cntForm, $emailContents);
	
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $fromMail = "noreply@kaamkaaj.com.pk";
    //$fromMail = "aqeelashraf@gmail.com";
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
}

function Contact_us_to_person($mTo,$Name){
    $fromMail="noreply@kaamkaaj.com.pk";
    $subject="KaamKaaj.com.pk";
    $site_url   = SITE_URL;
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=2");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{NAME}', $Name, $emailContents);
	
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}


function SuccessStory($fromMail, $Name,$msg,$tm_id){
    $subject="Success Story Request";
    $mTo="admin@kaamkaaj.com.pk";
    $site_url = SITE_URL;
    $approve='confirm_success_story.php?tm_id='.$tm_id.'&approval=1';
    $decile='confirm_success_story.php?tm_id='.$tm_id.'&decline=0';
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=3");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{APPROVE_DECLINE}', '<a href='.$site_url.$approve.'>Approve</a>&nbsp;&nbsp;<a href='.$site_url.$decile.'>Decline</a>', $emailContents);
	$emailContents = str_replace('{MESSAGE}', '$msg', $emailContents);
	
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>
<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}
</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>
</body>
</html>
    ';
	$fromMail = "noreply@kaamkaaj.com.pk";
	//$fromMail = "aqeelashraf@gmail.com";
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}




function SuccessStoryApproval($mTo, $user_fname,$user_lname){
    $subject="Success Story Approved";
    $fromMail="noreply@kaamkaaj.com.pk";
    $site_url   = SITE_URL;
    //$approve='confirm_success_story.php?tm_id='.$tm_id.'&approval=1';
   // $decile='confirm_success_story.php?tm_id='.$tm_id.'&decline=0';
    $link='success/stories/';
   $emailContents = "";
   $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=4");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{USER_NAME}', $user_fname.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{STORY_LINK}', '<a href='.$site_url.$link.'>here</a>', $emailContents);
	
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}



function SuccessStoryDecline($mTo, $user_fname,$user_lname){
    $subject="Success Story Request";
    $fromMail="noreply@kaamkaaj.com.pk";
    $site_url   = SITE_URL;
    //$approve='confirm_success_story.php?tm_id='.$tm_id.'&approval=1';
   // $decile='confirm_success_story.php?tm_id='.$tm_id.'&decline=0';
    //$link='success/stories/';
	$emailContents = "";
   $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=5");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{USER_NAME}', $user_fname.' '.$user_lname, $emailContents);
	
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}


function newjobposttoAdmin($jobtitle, $postdate, $j_lastdate,$job_id,$org_name,$user_fname,$user_lname, $orgLink){
    $subject='New Job Alert!';
    $mTo="admin@kaamkaaj.com.pk";
    //$fromMail="noreply@kaamkaaj.com.pk";
	$fromMail="aqeelashraf@gmail.com";
    $site_url   = SITE_URL;
    $approve='confirm_jobs_approval.php?job_id='.$job_id.'&approval=1';
    $decile='confirm_jobs_approval.php?job_id='.$job_id.'&decline=0';
	$jobDetails = '<table width="600" class="ecxJobsGrid" style="border:1px #e7e7e7 solid;border-collapse:collapse;font-family:Verdana;font-size:11px;padding:10px;text-decoration:none;margin-bottom: 20px;">
	<tbody>
		<tr>
			<td style="padding:5px;border-bottom:1px #e7e7e7 solid;" bgcolor="#f6f7f8" width="350" height="20"><strong>Job Title</strong></td>
			<td style="padding:5px;border-bottom:1px #e7e7e7 solid;" bgcolor="#f6f7f8" width="170" height="20"><strong>Company</strong></td>
			<td style="padding:5px;border-bottom:1px #e7e7e7 solid;" bgcolor="#f6f7f8" width="70" height="20"><strong>Date</strong></td>
		</tr>
		<tr>
			<td style="padding:7px;height:35px;border-bottom:1px #e7e7e7 solid;"><a href="'.$site_url.'#/jobs_details/'.$job_id.'" style="color:#0088cc;text-decoration:none;" target="_blank" class="">'.$jobtitle.'</a></td>
			<td style="padding:7px;border-bottom:1px #e7e7e7 solid;">'.$org_name.'</td>
			<td style="padding:7px;border-bottom:1px #e7e7e7 solid;">'.$postdate.'</td>
		</tr>
	</tbody>
</table>';
	
	$emailContents = '';
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=6");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
    	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
		$emailContents = str_replace('{USER_NAME}', $user_fname.' '.$user_lname, $emailContents);
		$emailContents = str_replace('{ORGANIZATION}', '<a href="'.$site_url.$orgLink.'">'.$org_name.'</a>', $emailContents);
		$emailContents = str_replace('{JOB_DETAILS}', $jobDetails, $emailContents);
		$emailContents = str_replace('{APPROVE_DECLINE}', '<a href='.$site_url.$approve.'>Approve</a>&nbsp;&nbsp;<a href='.$site_url.$decile.'>Decline</a>', $emailContents);
		$email_body = '
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="600" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
						'.$emailContents.'
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}


function JobApprovalToEmployer($jobtitle, $postdate, $toM ,$job_id,$org_name,$user_fname,$user_lname){
    $subject='Job Approval Alerts';
    $mTo=$toM;
    $fromMail="noreply@kaamkaaj.com.pk";
    $site_url   = SITE_URL;
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=7");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{USER_NAME}', $user_fname.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{JOB_TITLE}', $jobtitle, $emailContents);
	$emailContents = str_replace('{JOB_LINK_2}', '<a href="'.$site_url.'#/jobs_details/'.$job_id.'" style="color:#0088cc;text-decoration:none;" target="_blank" class="">here</a>', $emailContents);
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}

function JobRejectedToEmployer($jobtitle, $postdate, $toM ,$job_id,$org_name,$user_fname,$user_lname){
    $subject='Job Approval Alerts';
    $mTo=$toM;
    $fromMail="noreply@kaamkaaj.com.pk";
    $site_url   = SITE_URL;
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=16");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{USER_NAME}', $user_fname.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{JOB_TITLE}', $jobtitle, $emailContents);
	//$emailContents = str_replace('{JOB_LINK_2}', '<a href="'.$site_url.'jobs_details/'.$job_id.'" style="color:#0088cc;text-decoration:none;" target="_blank" class="">here</a>', $emailContents);
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}


function CandidateApply($user_fnamec, $user_lnamec,$user_fnameE,$user_lnameE,$user_nameE,$job_positionE,$jobid,$ja_id,$user_idc,$user_namec, $linkPathOrg, $org_name){
    $subject='Apply on Job';
    $mTo=$user_nameE;
    $fromMail="noreply@kaamkaaj.com.pk";
    $site_url = SITE_URL;
	$candidateNameURL = str_replace(" ", "-", $user_fnamec."-".$user_lnamec);
    $link='#/resume_view/'.$user_idc.'_'.$candidateNameURL.'/job/'.$ja_id;
	$emailContents="";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=8");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{USER_NAME}', $user_fnameE.' '.$user_lnameE, $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{JOB_LINK}', '<a href="'.$site_url.'#/jobs_details/'.$jobid.'" style="color:#0088cc;text-decoration:none;" target="_blank">'.$job_positionE.'</a>', $emailContents);
   	$emailContents = str_replace('{ORGANIZATION}', '<a href="'.$site_url.$linkPathOrg.'">'.$org_name.'</a>', $emailContents);
	$emailContents = str_replace('{CV}', '<a href="'.$site_url.$link.'" style="color:#0088cc;text-decoration:none;" target="_blank" class="">here</a>', $emailContents);
	$emailContents = str_replace('{CANDIDATE_NAME}', '<a href="'.$site_url.$link.'">'.$user_fnamec.' '.$user_lnamec.'</a>', $emailContents);
	$subject = str_replace('{USER_NAME}', $user_fnameE.' '.$user_lnameE, $row->eml_subject);
   $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}



function InterviewToCandidate($org_name,$jobtitle,$job_id, $user_name, $user_fname,$user_lname,$venue_title,$venue_address, $interview_date, $interview_time, $intid, $linkPathOrg){
    $subject='';
    $mTo=$user_name;
    $fromMail="noreply@kaamkaaj.com.pk";
    $site_url   = SITE_URL;
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=9");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$interviewDetails = 'Venue: '.$venue_title.'<br>Date: '.$interview_date.'<br>Time: '.$interview_time.'<br>ADdress: '.$venue_address;
	$confirmLink = '<a href="'.$site_url.'#/interview-confirm/'.$intid.'">Click Here</a>';
	$emailContents = str_replace('{USER_NAME}', $user_fname.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{JOB_LINK}', '<a href="'.$site_url.'#/jobs_details/'.$job_id.'" style="color:#0088cc;text-decoration:none;" target="_blank">'.$jobtitle.'</a>', $emailContents);
   	$emailContents = str_replace('{ORGANIZATION}', '<a href="'.$site_url.$linkPathOrg.'">'.$org_name.'</a>', $emailContents);
	$emailContents = str_replace('{INTERVIEW_DETAILS}', $interviewDetails, $emailContents);
	$emailContents = str_replace('{CLICK_HERE}', $confirmLink, $emailContents);
	$subject = str_replace('{USER_NAME}', $user_fname.' '.$user_lname, $row->eml_subject);
	$email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}

function InterviewConfirmation($user_id, $user_name, $user_fname, $user_lname, $empMail, $empFName, $empLName){
    $subject='';
    $mTo=$empMail;
    $fromMail="noreply@kaamkaaj.com.pk";
    $site_url = SITE_URL;
	$emailContents = "";
	$candidateNameURL = str_replace(" ", "-", $user_fname."-".$user_lname);
	$link='#/resume_view/'.$user_id.'_'.$candidateNameURL;
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=15");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{EMP_NAME}', $empFName.' '.$empLName, $emailContents);
	$emailContents = str_replace('{CANDIDATE_NAME}', '<a href="'.$site_url.$link.'">'.$user_fname.' '.$user_lname.'</a>', $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$subject = $row->eml_subject;
	$email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:auto;height:100%;" align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style="background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px;width:600px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; ">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}


function forgotPass($user_fnmae, $user_lname, $user_pass,$user_name){
    
    $mTo=$user_name;
    $subject='Reset password request';
    $fromMail='noreply@kaamkaaj.com.pk';
    $site_url   = SITE_URL;
	$emailContents="";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=10");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{USER_NAME}', $user_fnmae.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{USER_PASSWORD}', $user_pass, $emailContents);
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("'.$site_url.'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table cellpadding="0" cellspacing="0" border="0" style="margin:auto;width:100%;height:100%"  align="center">
    	<tr>
            <td>
            	<table  cellpadding="0" cellspacing="0" border="0" style=" background:url('.$site_url.'email/hdr_bg.jpg) 0px 0px repeat-x; width:600px; height:84px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="'.$site_url.'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; " align="center">
                        	<tr>
                            	<td >
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;" align="center">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
	//print($email_body);
	//die();
    $message  = $email_body;
    $headers  = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: ". strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";  
    @mail($mTo, $subject, $message, $headers);
   
}


function registrationCandidates($user_fnmae, $user_lname, $user_pass,$user_name,$mem_id){
    
    $mTo = $user_name;
    $subject = 'Welcome to kaamkaaj.com.pk!';
    $fromMail = 'noreply@kaamkaaj.com.pk';
    $site_url = SITE_URL;
    $confrm='manage_confirm.php?uid='.$mem_id;
	$emailContents="";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=11");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{USER_NAME}', $user_fnmae.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{CONFIRM_LINK}', '<a href="'.$site_url.$confrm.'">here</a>', $emailContents);
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="margin:auto;width:100%;height:100%" align="center">
    	<tr>
            <td>
            	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" background:url(' . $site_url . 'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="' . $site_url . 'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center"> 
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; " align="center">
                        	<tr>
                            	<td>
								'.$emailContents.'
								</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;" align="center">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>
</body>
</html>
    ';
    //print($email_body);
    //die();
    $message = $email_body;
    $headers = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: " . strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    @mail($mTo, $subject, $message, $headers);
}


function registrationEmployees($user_fnmae, $user_lname, $user_pass,$user_name,$mem_id){
    
    $mTo = $user_name;
    $subject = 'Welcome to kaamkaaj.com.pk!';
    $fromMail = 'noreply@kaamkaaj.com.pk';
    $site_url = SITE_URL;
    $confrm='manage_confirm.php?uid='.$mem_id;
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=12");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{USER_NAME}', $user_fnmae.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{CONFIRM_LINK}', '<a href="'.$site_url.$confrm.'">here</a>', $emailContents);
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="margin:auto;width:100%;height:100%" align="center">
    	<tr>
            <td>
            	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" background:url(' . $site_url . 'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="' . $site_url . 'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center"> 
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; " align="center">
                        	<tr>
                            	<td>
								'.$emailContents.'
								</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;" align="center">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
    //print($email_body);
    //die();
    $message = $email_body;
    $headers = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: " . strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    @mail($mTo, $subject, $message, $headers);
   
}




function WelComeProfessional($user_fnmae, $user_lname, $user_name){
$mTo = $user_name;
$subject = 'Welcome to kaamkaaj.com.pk!';
$fromMail = 'noreply@kaamkaaj.com.pk';
$site_url = SITE_URL;
//$confrm='manage_confirm.php?uid='.$mem_id;
$emailContents = "";
$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=13");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{USER_NAME}', $user_fnmae.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{CONFIRM_LINK}', '<a href="'.$site_url.'#/'.$confrm.'">here</a>', $emailContents);
$email_body = ' 
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="margin:auto;width:100%;height:100%" align="center">
    	<tr>
            <td>
            	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" background:url(' . $site_url . 'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="' . $site_url . 'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center"> 
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; " align="center">
                        	<tr>
                            	<td>
                                '.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;" align="center">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
    //print($email_body);
    //die();
    $message = $email_body;
    $headers = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: " . strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    @mail($mTo, $subject, $message, $headers);
   
}



function WelComeEmployer($user_fnmae, $user_lname, $user_name,$mem_id){
    $mTo = $user_name;
    $subject = 'Welcome to kaamkaaj.com.pk!';
    $fromMail = 'noreply@kaamkaaj.com.pk';
    $site_url = SITE_URL;
    $confrm='manage_confirm.php?uid='.$mem_id;
	$emailContents = "";
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=14");
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		$subject = $row->eml_subject;
		$fromMail = $row->eml_from_address;
		$emailContents = $row->eml_contents;
	}
	$emailContents = str_replace('{USER_NAME}', $user_fnmae.' '.$user_lname, $emailContents);
	$emailContents = str_replace('{POST_JOB_LINK}', '<a href="'.$site_url.'#/post_jobs">Post Jobs</a>', $emailContents);
	$emailContents = str_replace('{SITE_LINK}', '<a href="'.$site_url.'">kaamkaaj.com.pk</a>', $emailContents);
	$emailContents = str_replace('{CONFIRM_LINK}', '<a href="'.$site_url.'#/'.$confrm.'">here</a>', $emailContents);
    $email_body = ' 
    <!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>KaamKaaj</title>

<style>
@font-face {
  font-family: "ITCAvantGardeStd-MdCn";
  src: url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.eot?#iefix") format("embedded-opentype"),  url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.otf")  format("opentype"),
	     url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.woff") format("woff"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.ttf")  format("truetype"), url("' . $site_url . 'email/fonts/ITCAvantGardeStd-MdCn/ITCAvantGardeStd-MdCn.svg#ITCAvantGardeStd-MdCn") format("svg");
  font-weight: normal;
  font-style: normal;
}


</style>

</head>

<body>
	<table  cellpadding="0" cellspacing="0" border="0" style="margin:auto;width:100%;height:100%" align="center">
    	<tr>
            <td>
            	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" background:url(' . $site_url . 'email/hdr_bg.jpg) 0px 0px repeat-x; height:84px; padding:10px; border-top:2px solid #FD3511;" align="center">
                	<tr>
                    	<td><img src="' . $site_url . 'email/logo.png" alt=""></td>
                        <td>
                        	<table  border="0" style=" float:right;">
                            	<tr>
                                	<td style="margin-right:5px;"><a href="https://www.facebook.com/kaamkaajpak"><img src="'.$site_url.'email/social/img1.png" alt=""></a></td>
                                    <td style="margin-right:5px;"><a href="https://www.twitter.com/kaamkaajpk/"><img src="'.$site_url.'email/social/img2.png" alt=""></a></td>
                                    <td><a href="https://www.linkedin.com/in/kaamkaaj"><img src="'.$site_url.'email/social/img3.png" alt=""></a></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                	
                </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto; margin-bottom:20px;" align="center"> 
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px; " align="center">
                        	<tr>
                            	<td>
								'.$emailContents.'
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        <tr>
        <td>
        	<table width="100" cellpadding="0" cellspacing="0" border="0" style="margin:auto;" align="center">
            	<tr>
                	<td>
                    	<table width="600" cellpadding="0" cellspacing="0" border="0" style=" padding:10px;  background-color:#F4F4F4; border-top:3px solid #FD310D;" align="center">
                        	<tr>
                            	<td align="center" style="font-size:13px; color:#464646;  font-family: Arial, sans-serif;">
                                	&copy; KaamKaaj 2015. All Rights Reserved
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        
        
    </table>
</body>
</html>

    ';
    //print($email_body);
    //die();
    $message = $email_body;
    $headers = "From: KaamKaaj <" . strip_tags($fromMail) . ">\r\n";
    $headers .= "Reply-To: " . strip_tags($fromMail) . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    @mail($mTo, $subject, $message, $headers);
   
}
?>
