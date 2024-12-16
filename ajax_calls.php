<?php
include("includes/php_includes_top.php");
if(isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'switch_click':
            echo $_SESSION['utype_id'] = $_REQUEST['utype_id']; 
            break;
        }
    }