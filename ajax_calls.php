<?php
include("includes/php_includes_top.php");
if(isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'utype_id':
            echo $_SESSION['utype_id'] = $_REQUEST['utype_id']; 
            break;
        }
    }