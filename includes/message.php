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
			$strMSG = "Dear Cuctomer, <br>
            your account has been created successfully. Please <a href='login.php'>log in</a> to your account  and enjoy our services";
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
	}
}
?>