<?php
if(isset($_REQUEST['op'])){
	switch ($_REQUEST['op']) {
		case 1:
			$class = "alert alert-success";
			$strMSG = "Record Added Successfully";
			break;
		case 2:
			$strMSG = " Record Updated Successfully";
			$class = "alert alert-success";
			break;
        case 3:
			$class = "alert alert-success";
			$strMSG = "Record(s) deleted Successfully";
			break;
        case 4:
			$class = "alert alert-danger";
			$strMSG = "Dear Cuctomer, <br>
                       This account already exists against the user name!";
			break;
        case 5:
			$class = "alert alert-danger";
			$strMSG = "Dear Cuctomer, <br>
                       Passwords does not match!";
			break;
        case 6:
			$class = "alert alert-success";
			$strMSG = "Dear Cuctomer, <br>
            your account has been created successfully. Please <a href='login.php'>log in</a> to your account  and enjoy our services";
			break;
		case 7:
			$class = "alert alert-danger";
			$strMSG = "Your old password is wrong";
			break;
		case 8:
			$class = "alert alert-success";
			$strMSG = "<strong>Success!</strong> Thank you for contacting us.";
			break;
		case 9:
			$class = "alert alert-success";
			$strMSG = "Your request for a change in password has been successfully completed. Please log in to access your account with new password. Please see your email account for a new password credential.";
			break;
		case 10:
			$class = "alert alert-danger";
			$strMSG = "Record added fail!";
			break;
		case 11:
			$class = "alert alert-danger";
			$strMSG = "Issue in your new and confirm password";
			break;
		case 12:
			$class = "alert alert-danger";
			$strMSG = "Please add the defualt address";
			break;
		case 13:
			$class = "alert alert-danger";
			$strMSG = "Dear Cuctomer, <br> Confirmation code does not match!";
			break;
		case 14:
			$class = "alert alert-success";
			$strMSG = "Record already exists";
			break;
	}
}
?>