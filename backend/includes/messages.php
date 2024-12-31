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
            $strMSG = "Please check atleast one checkbox";
            $class = " alert alert-danger ";
             break;
		case 4:
			$class = "notification success";
			$strMSG = "Please Select Checkbox to Add or Subtract Credits";
			break;
		case 5:
			$class = "alert alert-success";
			$strMSG = "Record(s) deleted Successfully";
			break;
		case 6:
			$class = "alert alert-danger";
			$strMSG = "The record already exists against the following page and params";
			break;
		case 7:
			$class = "alert alert-danger";
			$strMSG = "Passwords does not match!";
			break;
		case 8:
			$class = "alert alert-danger";
			$strMSG = "Your old password is wrong";
			break;
		case 9:
			$class = "alert alert-success";
			$strMSG = "Your request for a change in password has been successfully completed. Please log in to access your account with new password.";
			break;
		case 10:
			$class = "alert alert-danger";
			$strMSG = "The record already exists against the following username";
			break;
		case 11:
			$class = "alert alert-info";
			$strMSG = "Please check the image format otherwise online convert the image extension with a jpeg";
			break;
	}
}
?>