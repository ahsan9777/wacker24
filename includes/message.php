<?php
if(isset($_REQUEST['op'])){
	switch ($_REQUEST['op']) {
		case 1:
			$class = "alert alert-success";
			//$strMSG = "Record Added Successfully";
			$strMSG = "Rekord erfolgreich hinzugefügt";
			break;
		case 2:
			$class = "alert alert-success";
			//$strMSG = "Record Updated Successfully";
			$strMSG = "Rekord erfolgreich aktualisiert";
			break;
        case 3:
			$class = "alert alert-danger";
			//$strMSG = "Record(s) deleted Successfully";
			$strMSG = "Datensatz(e) erfolgreich gelöst";
			break;
        case 4:
			$class = "alert alert-danger";
			//$strMSG = "Dear Cuctomer, <br>This account already exists against the user name!";
			$strMSG = "Sehr geehrter Kunde, <br> Dieses Konto existiert bereits unter diesem Benutzernamen!";
			break;
        case 5:
			$class = "alert alert-danger";
			//$strMSG = "Dear Cuctomer, <br> Passwords does not match!";
			$strMSG = "Sehr geehrter Kunde, <br> Die Kennwörter stimmen nicht überein!";
			break;
        case 6:
			$class = "alert alert-success";
			$strMSG = "Sehr geehrter Kunde, <br>Ihr Konto wurde erfolgreich erstellt. Melden Sie sich bitte bei Ihrem Konto an und nutzen Sie unsere Dienste";
			break;
		case 7:
			$class = "alert alert-danger";
			//$strMSG = "Your old password is wrong";
			$strMSG = "Ihr altes Kennwort ist falsch";
			break;
		case 8:
			$class = "alert alert-success";
			$strMSG = "<strong>Erfolgreich!</strong> Vielen Dank, dass Sie mit uns Kontakt aufgenommen haben.";
			break;
		case 9:
			$class = "alert alert-success";
			$strMSG = "Your request for a change in password has been successfully completed. Please log in to access your account with new password. Please see your email account for a new password credential.";
			break;
		case 10:
			$class = "alert alert-danger";
			//$strMSG = "Record added fail!";
			$strMSG = "Rekordverdächtiger Misserfolg!";
			break;
		case 11:
			$class = "alert alert-danger";
			//$strMSG = "Issue in your new and confirm password";
			$strMSG = "Geben Sie Ihr neues, bestätigtes Passwort ein";
			break;
		case 12:
			$class = "alert alert-danger";
			//$strMSG = "Please add the defualt address";
			$strMSG = "Bitte fügen Sie die Standard-Adresse hinzu";
			break;
		case 13:
			$class = "alert alert-danger";
			//$strMSG = "Dear Cuctomer, <br> Confirmation code does not match!";
			$strMSG = "Sehr geehrter Kunde,<br> Der Bestätigungscode passt nicht!";
			break;
		case 14:
			$class = "alert alert-danger";
			//$strMSG = "Record already exists";
			$strMSG = "Eintrag bereits vorhanden";
			break;
		case 15:
			$class = "alert alert-success";
			$strMSG = "<strong>Bestellung aufgegeben!</strong> Herzlichen Glückwunsch Ihre Bestellung wurde erfolgreich aufgegeben";
			break;
		case 16:
			$class = "alert alert-danger";
			$strMSG = "Bitte geben Sie Ihre Rechnungsanschrift ein";
			break;
		case 17:
			$class = "alert alert-danger";
			$strMSG = "Ungültige Kreditkartennummer, Bankkontonummer oder Bankname";
			break;
		case 18:
			$class = "alert alert-danger";
			$strMSG = "Karte abgelaufen";
			break;
		case 19:
			$class = "alert alert-danger";
			$strMSG = "Transaktion abgelehnt – verdächtige Aktivität erkannt";
			break;
		case 20:
			$class = "alert alert-danger";
			$strMSG = "Ungültiger CVV-Code";
			break;
		case 21:
			$class = "alert alert-danger";
			$strMSG = "Technischer Fehler – bitte versuchen Sie es später erneut";
			break;
		case 22:
			$class = "alert alert-danger";
			$strMSG = "Unbekannter Fehler – bitte kontaktieren Sie den Support";
			break;
		case 23:
			$class = "alert alert-danger";
			$strMSG = "Ungültige Authentifizierungsinformationen";
			break;
		case 24:
			$class = "alert alert-success";
			$strMSG = "Ihr Konto wurde erfolgreich verifiziert";
			break;
		case 25:
			$class = "alert alert-danger";
			$strMSG = "Ihr Konto wurde nicht verifiziert. Bitte kontaktieren Sie unser Team";
			break;
		case 26:
			$class = "alert alert-success";
			$strMSG = '<b>Vielen Dank für Ihre Bestellung!</b>
						<br><br>Sie haben als Zahlungsart <b>Vorkasse</b> gewählt.
						<br><br>Bitte überweisen Sie den Gesamtbetrag Ihrer Bestellung <b>innerhalb von 7 Tagen</b> auf folgendes Konto:
						<br><br><b>Wacker Bürocenter GmbH</b>
						<br>Bank: <b>VR-Bank Südpfalz</b>
						<br>IBAN: <b>DE95 5486 2500 0006 7025 70</b>
						<br>BIC: <b>GENODE61SUW</b>
						<br><br>Verwendungszweck:  <b>'.$_REQUEST['ord_id'].'</b>
						<br><br>Nach Zahlungseingang erhalten Sie eine Bestätigung per E-Mail. Anschließend wird Ihre Bestellung schnellstmöglich versendet.
						<br><br><b><img style="width: 20px;" data-emoji="📞" class="an1" alt="📞" aria-label="📞" draggable="false" src="'.$GLOBALS['siteURL'].'images/phone.png" loading="lazy" data-emailtracker-detector="1"> Bei Fragen sind wir gerne für Sie da:</b>
						<br><br>Hotline: <b>06321 9124-80</b>
						<br>E-Mail: <a href="mailto:'.$GLOBALS['vorkasse_email'].'" style="color:rgb(70,120,134)" target="_blank"><b>'.$GLOBALS['vorkasse_email'].'</b></a>
						<br><br>Vielen Dank für Ihr Vertrauen!
						<br><br>Mit freundlichen Grüßen
						<br><br>Ihr Team vom <b>Wacker Bürocenter</b>';
			break;
	}
}
?>