<?php
include("../lib/session_head.php");
$formHead = "Add New";


$searchQuery = "";
//$searchQuery = "WHERE 1 = 1";
if (isset($utype_id) && $utype_id == 0) {
    $utype_id = $utype_id;
    $utype_where = " utype_id IN (1,2)";
} else {
    $utype_id = 1;
    $pHead = "User Management";
    $utype_where = " utype_id IN (3,4)";
}

if (isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    $Query = "SELECT * FROM `users` WHERE user_name ='" . dbStr(trim($_REQUEST['user_name'])) . "' AND utype_id = '" . dbStr(trim($_REQUEST['utype_id'])) . "'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {

        $utype_id = $_REQUEST['utype_id'];
        $user_company_name = $_REQUEST['user_company_name'];
        $user_fname = $_REQUEST['user_fname'];
        $user_lname = $_REQUEST['user_lname'];
        $gen_id = $_REQUEST['gen_id'];
        $user_phone = $_REQUEST['user_phone'];
        $user_name = $_REQUEST['user_name'];
        $user_password = $_REQUEST['user_password'];
        $user_confirm_password = $_REQUEST['user_confirm_password'];
        $countries_id = $_REQUEST['countries_id'];
        $class = "alert alert-danger";
        $strMSG = "Dear Admin, This account already exists against the user name and type!";
    } else {
        if ($_REQUEST['user_password'] != $_REQUEST['user_confirm_password']) {

            $utype_id = $_REQUEST['utype_id'];
            $user_company_name = $_REQUEST['user_company_name'];
            $user_fname = $_REQUEST['user_fname'];
            $user_lname = $_REQUEST['user_lname'];
            $gen_id = $_REQUEST['gen_id'];
            $user_phone = $_REQUEST['user_phone'];
            $user_name = $_REQUEST['user_name'];
            $user_password = $_REQUEST['user_password'];
            $user_confirm_password = $_REQUEST['user_confirm_password'];
            $countries_id = $_REQUEST['countries_id'];

            $class = "alert alert-danger";
            $strMSG = "Dear Admin, confirmation does not match!";
        } else {
            $user_id = getMaximum("users", "user_id");
            mysqli_query($GLOBALS['conn'], "INSERT INTO users (user_id, utype_id, user_company_name, user_fname, user_lname, gen_id, user_phone, user_name, user_password, countries_id) VALUES ('" . $user_id . "', '" . dbStr(trim($_REQUEST['utype_id'])) . "', '" . dbStr(trim($_REQUEST['user_company_name'])) . "', '" . dbStr(trim($_REQUEST['user_fname'])) . "','" . dbStr(trim($_REQUEST['user_lname'])) . "','" . $_REQUEST['gen_id'] . "','" . dbStr(trim($_REQUEST['user_phone'])) . "','" . dbStr(trim($_REQUEST['user_name'])) . "','" . dbStr(password_hash(trim($_REQUEST['user_password']), PASSWORD_BCRYPT)) . "','" . dbStr(trim($_REQUEST['countries_id'])) . "')") or die(mysqli_error($GLOBALS['conn']));
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
        }
    }
} elseif (isset($_REQUEST['btnPasswordChange'])) {
    if ($_REQUEST['user_password'] != $_REQUEST['user_confirm_password']) {
        $readonly = "readonly";
        $user_fname = $_REQUEST['user_fname'];
        $user_lname = $_REQUEST['user_lname'];
        $user_password = $_REQUEST['user_password'];
        $user_confirm_password = $_REQUEST['user_confirm_password'];
        
        $class = "alert alert-danger";
        $strMSG = "Dear Admin, confirmation does not match!";
    } else{
    mysqli_query($GLOBALS['conn'], "UPDATE users SET user_password = '".dbStr(password_hash(trim($_REQUEST['user_password']), PASSWORD_BCRYPT)) . "' WHERE user_id=" . $_REQUEST['user_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?op=2");
    }
} elseif (isset($_REQUEST['btnUpdate'])) {
    $Query = "SELECT * FROM `users` WHERE user_name ='" . dbStr(trim($_REQUEST['user_name'])) . "' AND utype_id = '" . dbStr(trim($_REQUEST['utype_id'])) . "' AND user_id != '" . dbStr(trim($_REQUEST['user_id'])) . "'";
    //print($Query);die();
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=2&user_id=" . $_REQUEST['user_id'] . "&op=4");
    } else {
        mysqli_query($GLOBALS['conn'], "UPDATE users SET utype_id = '" . $_REQUEST['utype_id'] . "', user_company_name = '" . dbStr(trim($_REQUEST['user_company_name'])) . "',  user_fname='" . dbStr(trim($_REQUEST['user_fname'])) . "', user_lname = '" . dbStr(trim($_REQUEST['user_lname'])) . "', gen_id = '" . dbStr(trim($_REQUEST['gen_id'])) . "', user_phone = '" . dbStr(trim($_REQUEST['user_phone'])) . "', countries_id = '" . dbStr(trim($_REQUEST['countries_id'])) . "' WHERE user_id=" . $_REQUEST['user_id']) or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=2");
    }
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == in_array($_REQUEST['action'], array(2,3))) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE user_id = " . $_REQUEST['user_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $utype_id = $rsMem->utype_id;
            $user_company_name = $rsMem->user_company_name;
            $gen_id = $rsMem->gen_id;
            $user_fname = $rsMem->user_fname;
            $user_lname = $rsMem->user_lname;
            $user_name = $rsMem->user_name;
            $user_phone = $rsMem->user_phone;
            $countries_id = $rsMem->countries_id;
            $user_password = create_password(13);
            $user_confirm_password = $user_password;
            $readonly = "readonly";
            $formHead = "Update Info";
        }
    } else {
        $utype_id = 0;
        $user_company_name = "";
        $gen_id = 1;
        $user_fname = "";
        $user_lname = "";
        $user_name = "";
        $user_password = create_password(13);
        $user_confirm_password = $user_password;
        $user_phone = "";
        $countries_id = 81;
        $readonly = "";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE users SET status_id='1' WHERE user_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button InActive--------------------
if (isset($_REQUEST['btnInactive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE users SET status_id='0' WHERE user_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}

include("includes/messages.php");

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
</head>

<body>
    <div class="container_main">
        <!-- Sidebar -->
        <?php include("includes/sidebar.php"); ?>

        <!-- Main content -->
        <div class="main-content">
            <!-- Top bar -->
            <?php include("includes/topbar.php"); ?>

            <!-- Content -->
            <section class="content" id="main-content">
                <?php if ($class != "") { ?>
                    <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                <?php } ?>
                <?php if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], array(1, 2))) { ?>
                    <div class="main_container">
                        <h2 class="text-white">
                            <?php print($formHead); ?> User
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Type</label>
                                    <select name="utype_id" id="utype_id" class="input_style">
                                        <?php FillSelected2("user_type", "utype_id", "utype_name", $utype_id, $utype_where); ?>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Company</label>
                                    <input type="text" class="input_style" name="user_company_name" id="user_company_name" value="<?php print($user_company_name); ?>" placeholder="Company">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">First Name</label>
                                    <input type="text" class="input_style" required name="user_fname" id="user_fname" value="<?php print($user_fname); ?>" placeholder="First Name">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Last Name</label>
                                    <input type="text" class="input_style" name="user_lname" id="user_lname" value="<?php print($user_lname); ?>" placeholder="Last Name">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Gender</label>
                                    <select name="gen_id" class="input_style" id="gen_id">
                                        <option value="1" <?php print(($gen_id == '1') ? 'selected' : ''); ?>> Male </option>
                                        <option value="2" <?php print(($gen_id == '2') ? 'selected' : ''); ?>> Female </option>
                                        <option value="3" <?php print(($gen_id == '3') ? 'selected' : ''); ?>> Other </option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Email</label>
                                    <input type="email" <?php print($readonly); ?> class="input_style" required name="user_name" id="user_name" value="<?php print($user_name); ?>" placeholder="Email">
                                </div>
                                <?php if ($_REQUEST['action'] == 1) { ?>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Password</label>
                                        <div class="d-flex">
                                            <input type="text" class="input_style" required name="user_password" id="user_password" value="<?php print($user_password); ?>" placeholder="Password">
                                            <button class="btn btn-outline-secondary w-auto" title="Generate Password" type="button" onclick="generateRandomString(13);"><span class="material-icons icon">key</span></button>
                                        </div>
                                        <div class="d-flex gap-2 mt-3">
                                            <label for="">Show Password: </label>
                                            <input type="checkbox" name="show_password" id="show_password" checked>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-3">
                                        <label for="">Confirm Password</label>
                                        <input type="text" class="input_style" required name="user_confirm_password" id="user_confirm_password" value="<?php print($user_confirm_password); ?>" placeholder="Confirm Password">
                                        <div class="d-flex gap-2 mt-3">
                                            <label for="">Show Confirm Password: </label>
                                            <input type="checkbox" name="show_confirm_password" id="show_confirm_password" checked>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Contact No</label>
                                    <input type="text" class="input_style" name="user_phone" id="user_phone" value="<?php print($user_phone); ?>" placeholder="Contact No">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Country</label>
                                    <select name="countries_id" class="input_style" id="countries_id">
                                        <?php FillSelected2("countries", "countries_id", "countries_name ", $countries_id, "countries_id > 0"); ?>
                                    </select>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <button class="btn btn-primary" type="submit" name="<?php print(($_REQUEST['action'] == 1) ? 'btnAdd' : 'btnUpdate'); ?>">Upload</button>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 3) { ?>
                    <div class="main_container">
                        <h2 class="text-white">
                            Change Password
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <div class="row">
                            <div class="col-md-6 col-12 mt-3">
                                    <label for="">First Name</label>
                                    <input type="text" class="input_style" <?php print($readonly); ?> name="user_fname" id="user_fname" value="<?php print($user_fname); ?>" placeholder="First Name">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Last Name</label>
                                    <input type="text" class="input_style" <?php print($readonly); ?> name="user_lname" id="user_lname" value="<?php print($user_lname); ?>" placeholder="Last Name">
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Password</label>
                                    <div class="d-flex">
                                        <input type="text" class="input_style" required name="user_password" id="user_password" value="<?php print($user_password); ?>" placeholder="Password">
                                        <button class="btn btn-outline-secondary w-auto" title="Generate Password" type="button" onclick="generateRandomString(13);"><span class="material-icons icon">key</span></button>
                                    </div>
                                    <div class="d-flex gap-2 mt-3">
                                        <label for="">Show Password: </label>
                                        <input type="checkbox" name="show_password" id="show_password" checked>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Confirm Password</label>
                                    <input type="text" class="input_style" required name="user_confirm_password" id="user_confirm_password" value="<?php print($user_confirm_password); ?>" placeholder="Confirm Password">
                                    <div class="d-flex gap-2 mt-3">
                                        <label for="">Show Confirm Password: </label>
                                        <input type="checkbox" name="show_confirm_password" id="show_confirm_password" checked>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <button class="btn btn-primary" type="submit" name="btnPasswordChange">Upload</button>
                                    <button type="button" name="btnBack" class="btn btn-light" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white"> <?php print($pHead); ?> </h1>
                        <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>

                    </div>
                    <div class="main_table_container">
                        <?php

                        $user_id = 0;
                        //$utype_id = 0;
                        $user_full_name = "";

                        if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
                            if (!empty($_REQUEST['user_full_name'])) {
                                $user_id = $_REQUEST['user_id'];
                                $user_full_name = $_REQUEST['user_full_name'];
                                $searchQuery = " AND u.user_id = '" . $_REQUEST['user_id'] . "'";
                            }
                        }

                        if (isset($_REQUEST['utype_id']) && $_REQUEST['utype_id'] > 0) {
                            $utype_id = $_REQUEST['utype_id'];
                            $searchQuery = " AND u.utype_id = '" . $_REQUEST['utype_id'] . "'";
                        }
                        ?>
                        <?php if ($utype_id > 0) { ?>
                            <form class="row flex-row" name="frm_search" id="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                                <div class=" col-md-3 col-12 mt-2">
                                    <label for="" class="text-white">Title</label>
                                    <input type="hidden" name="user_id" id="user_id" value="<?php print($user_id); ?>">
                                    <input type="text" class="input_style user_full_name" name="user_full_name" id="user_full_name" value="<?php print($user_full_name); ?>" placeholder="Title:" autocomplete="off" onchange="javascript: frm_search.submit();">
                                </div>
                                <div class=" col-md-2 col-12 mt-2">
                                    <label for="" class="text-white">Type</label>
                                    <select name="utype_id" id="utype_id" class="input_style" onchange="javascript: frm_search.submit();">
                                        <option value="0">N/A</option>
                                        <?php FillSelected2("user_type", "utype_id", "utype_name", $utype_id, $utype_where); ?>
                                    </select>
                                </div>
                            </form>
                        <?php } ?>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <th width="150">Customer Id </th>
                                        <th width="300">User Info </th>
                                        <?php if ($utype_id > 0) { ?>
                                            <th width="95">Zip Code</th>
                                            <th>Street</th>
                                        <?php } ?>
                                        <th width="200">Type</th>
                                        <th width="180">Created</th>
                                        <?php if ($utype_id > 0) { ?>
                                            <th class="text-end" width="190">Payment Methods</th>
                                        <?php } ?>
                                        <th width="50">Status</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT u.*, ut.utype_name, usa.usa_zipcode, usa.usa_street, usa.usa_address FROM users AS u LEFT OUTER JOIN user_type AS ut ON ut.utype_id = u.utype_id LEFT OUTER JOIN user_shipping_address AS usa ON usa.user_id = u.user_id AND usa.usa_defualt = '1' WHERE u." . $utype_where . " " . $searchQuery . " ";
                                    //print($Query);
                                    $counter = 0;
                                    $limit = 25;
                                    $start = $p->findStart($limit);
                                    $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                    $pages = $p->findPages($count, $limit);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $counter++;
                                            $strClass = 'label  label-danger';
                                            $user_company_name = "";
                                            if(!empty($row->user_company_name) && $row->utype_id == 4){
                                                $user_company_name = '<span class="btn btn-warning btn-style-light w-auto mb-2 d-flex align-items-center gap-1" style="font-size: 12px;"><span class="material-icons icon material-xs">apartment</span> <span>'.$row->user_company_name.' </span></span>';
                                            }

                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->user_id); ?>"></td>
                                                <td>
                                                    <input type="hidden" name="user_id" id="user_id_<?php print($row->user_id); ?>" value="<?php print($row->user_id); ?>">
                                                    <input type="number" data-id="<?php print($row->user_id); ?>" class="input_style customer_id" name="customer_id" id="customer_id_<?php print($row->user_id); ?>" value="<?php print($row->customer_id); ?>">
                                                </td>
                                                <td><?php print($user_company_name. $row->user_fname . " " . $row->user_lname."<br>".$row->user_name); ?></td>
                                                <?php if ($utype_id > 0) { ?>
                                                    <td><?php print($row->usa_zipcode); ?></td>
                                                    <td><?php print($row->usa_street); ?></td>
                                                <?php } ?>
                                                <td>
                                                    <?php
                                                    if ($row->utype_id == 4 || $row->utype_id == 1) {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">' . $row->utype_name . '</span>';
                                                    } else {
                                                        echo '<span class="btn btn-primary btn-style-light w-auto">' . $row->utype_name  . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php print(date('D F j, Y', strtotime($row->user_datecreated))); ?></td>
                                                <?php if ($utype_id > 0) { ?>
                                                    <td>
                                                        <div class="d-flex flex-column align-items-end gap-2">
                                                            <div>
                                                                <label class="fw-bold">Bill Payment:</label> <input type="checkbox" class="user_invoice_payment" id="user_invoice_payment" data-id="<?php print($row->user_id); ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" <?php print(($row->user_invoice_payment == 1) ? 'checked' : ''); ?>>
                                                            </div>
                                                            <div>
                                                                <label class="fw-bold">Sepa Payment:</label> <input type="checkbox" class="user_sepa_payment" id="user_sepa_payment" data-id="<?php print($row->user_id); ?>" data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm" <?php print(($row->user_sepa_payment == 1) ? 'checked' : ''); ?>>
                                                            </div>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <?php
                                                    if ($row->status_id == 0) {
                                                        echo '<span class="btn btn-danger btn-style-light w-auto">Offline</span>';
                                                    } else {
                                                        echo '<span class="btn btn-success btn-style-light w-auto">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "user_id=" . $row->user_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" title="Change Password" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=3&" . $qryStrURL . "user_id=" . $row->user_id); ?>';"><span class="material-icons icon material-xs">key</span></button>
                                                    <?php if ($utype_id > 0) { ?>
                                                        <button type="button" class="btn btn-xs btn-warning btn-style-light w-auto mt-2" title="Special Price" onClick="javascript: window.location = '<?php print("manage_special_price.php?user_id=" . $row->user_id); ?>';"><span class="material-icons icon material-xs">sell</span></button>
                                                    <?php } ?>
                                                    <?php
                                                    $user_order_count = TotalRecords("ord_id", "orders", "WHERE user_id = '" . $row->user_id . "' ");
                                                    if ($user_order_count > 0) {
                                                    ?>
                                                        <button type="button" class="btn btn-xs btn-info btn-style-light w-auto mt-2" title="User Order" onClick="javascript: window.location = '<?php print("manage_user_orders.php?user_id=" . $row->user_id); ?>';"><span class="material-icons icon material-xs">shopping_cart</span></button>
                                                    <?php } ?>
                                                    <!--<button type="button" class="btn btn-xs btn-warning btn-style-light w-auto" title="Add Product List" onClick="javascript: window.location = '<?php print("manage_add_product_list.php?user_id=" . $row->user_id); ?>';"><span class="material-icons icon material-xs">add</span></button>-->
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php if ($counter > 0) { ?>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><?php print("Page <b>" . $_GET['page'] . "</b> of " . $pages); ?></td>
                                        <td style="float: right;">
                                            <ul class="pagination" style="margin: 0px;">
                                                <?php
                                                $pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
                                                print($pageList);
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            <?php } ?>
                            <div class="row">
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnActive" value="Active" class="btn btn-primary btn-style-light w-100">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-style-light w-100">
                                </div>
                            </div>
                            <!--<input type="submit" name="btnDelete" onclick="return confirm('Are you sure you want to delete selected item(s)?');" value="Delete" class="btn btn-danger btn-style-light">-->
                        </form>

                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>

<script>
    function generateRandomString(len) {
        const pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        let str = '';

        for (let i = 0; i < len; i++) {
            const randomIndex = Math.floor(Math.random() * pool.length);
            str += pool[randomIndex];
        }
        $("#user_password").val(str);
        $("#user_confirm_password").val(str);
        //return str;
    }
    $("#password_show").on("click", function() {

    });
    $('input.user_full_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=user_full_name',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);

                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            var user_id = $("#user_id");
            var user_full_name = $("#user_full_name");
            $(user_id).val(ui.item.user_id);
            $(user_full_name).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
    $(document).ready(function() {
        // Listen for toggle changes
        
        $('.customer_id').change(function() {
            let id = $("#user_id_"+$(this).attr('data-id')).val();
            let set_field_data = $("#customer_id_"+$(this).attr('data-id')).val();
            //console.log("user_id: "+user_id+" customer_id: "+customer_id);
           $.ajax({
                url: 'ajax_calls.php?action=btn_toggle',
                method: 'POST',
                data: {
                    table: "users",
                    set_field: "customer_id",
                    set_field_data: set_field_data,
                    where_field: "user_id",
                    id: id
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    console.log(obj);
                    if (obj.status == 1 && set_field_data > 0) {
                        $.toast({
                            heading: 'Success',
                            text: 'Record updated successfully!',
                            icon: 'success',
                            position: 'top-right'
                        });
                    } else if (obj.status == 1 && set_field_data == 0) {
                        $.toast({
                            heading: 'Warning',
                            text: 'Record empty successfully!',
                            icon: 'warning',
                            position: 'top-right'
                        });
                    }
                }
            });
        });
        $('.user_invoice_payment').change(function() {
            let id = $(this).attr('data-id');
            let set_field_data = 0;
            //console.log("user_id: "+user_id)
            if ($(this).prop('checked')) {
                set_field_data = 1;
            }
            //console.log("set_field_data: "+set_field_data);
            $.ajax({
                url: 'ajax_calls.php?action=btn_toggle',
                method: 'POST',
                data: {
                    table: "users",
                    set_field: "user_invoice_payment",
                    set_field_data: set_field_data,
                    where_field: "user_id",
                    id: id
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    console.log(obj);
                    if (obj.status == 1 && set_field_data == 1) {
                        $.toast({
                            heading: 'Success',
                            text: 'Toggle is ON',
                            icon: 'success',
                            position: 'top-right'
                        });
                    } else if (obj.status == 1 && set_field_data == 0) {
                        $.toast({
                            heading: 'Warning',
                            text: 'Toggle is OFF',
                            icon: 'warning',
                            position: 'top-right'
                        });
                    }
                }
            });
        });
        $('.user_sepa_payment').change(function() {
            let id = $(this).attr('data-id');
            let set_field_data = 0;
            //console.log("user_id: "+id)
            if ($(this).prop('checked')) {
                set_field_data = 1;
            }
            //console.log("set_field_data: "+set_field_data);
            $.ajax({
                url: 'ajax_calls.php?action=btn_toggle',
                method: 'POST',
                data: {
                    table: "users",
                    set_field: "user_sepa_payment",
                    set_field_data: set_field_data,
                    where_field: "user_id",
                    id: id
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    console.log(obj);
                    if (obj.status == 1 && set_field_data == 1) {
                        $.toast({
                            heading: 'Success',
                            text: 'Toggle is ON',
                            icon: 'success',
                            position: 'top-right'
                        });
                    } else if (obj.status == 1 && set_field_data == 0) {
                        $.toast({
                            heading: 'Warning',
                            text: 'Toggle is OFF',
                            icon: 'warning',
                            position: 'top-right'
                        });
                    }
                }
            });
        });
    });

    $('#show_password').click(function() {
        $(this).is(':checked') ? $('#user_password').attr('type', 'text') : $('#user_password').attr('type', 'password');
    });
    $('#show_confirm_password').click(function() {
        $(this).is(':checked') ? $('#user_confirm_password').attr('type', 'text') : $('#user_confirm_password').attr('type', 'password');
    });
</script>

</html>