<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnUpdateQuantity'])) {
    print_r($_REQUEST);
    die();

    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['btnUpdatePrice'])) {
    print_r($_REQUEST);
    die();
    mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET pq_quantity = '" . dbStr(trim($_REQUEST['pq_quantity'])) . "', pq_upcomming_quantity = '" . dbStr(trim($_REQUEST['pq_upcomming_quantity'])) . "' WHERE pq_id = '" . dbStr(trim($_REQUEST['pq_id'])) . "' AND supplier_id = '" . dbStr(trim($_REQUEST['supplier_id'])) . "' AND pro_id = '" . dbStr(trim($_REQUEST['pro_id'])) . "' ") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
}
if (isset($_REQUEST['btnImport'])) {
    //print_r($_REQUEST);die();
    $xml = simplexml_load_file("lagersortiment_standard.xml") or die("Error: Cannot create object");
    //print('<pre>');
    //print_r($xml->T_NEW_CATALOG->ARTICLE->ARTICLE_DETAILS);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->ARTICLE_DETAILS->KEYWORD);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->ARTICLE_FEATURES);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->ARTICLE_ORDER_DETAILS);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->ARTICLE_PRICE_DETAILS->ARTICLE_PRICE[1]);
    //print_r($xml->T_NEW_CATALOG->ARTICLE->MIME_INFO->MIME[0]);
    //print('</pre>');die();
    mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status = '0'") or die(mysqli_error($GLOBALS['conn']));
    foreach ($xml->T_NEW_CATALOG->ARTICLE as $rl) {
        //echo $i++." ".$rl->ART_ID.PHP_EOL;
        $pro_uid = 0;
        $pro_id = getMaximum("products", "pro_id");
        $supplier_id = isset($rl->SUPPLIER_AID) ? $rl->SUPPLIER_AID : '';
        $pro_description_short = isset($rl->ARTICLE_DETAILS->DESCRIPTION_SHORT) ? $rl->ARTICLE_DETAILS->DESCRIPTION_SHORT : '';
        $pro_description_long = isset($rl->ARTICLE_DETAILS->DESCRIPTION_LONG) ? $rl->ARTICLE_DETAILS->DESCRIPTION_LONG : '';
        $pro_ean = isset($rl->ARTICLE_DETAILS->EAN) ? $rl->ARTICLE_DETAILS->EAN : '';
        $pro_buyer_id = isset($rl->ARTICLE_DETAILS->BUYER_AID) ? $rl->ARTICLE_DETAILS->BUYER_AID : '';
        $pro_manufacture_aid = isset($rl->ARTICLE_DETAILS->MANUFACTURER_AID) ? $rl->ARTICLE_DETAILS->MANUFACTURER_AID : '';
        $pro_manufacture_name = isset($rl->ARTICLE_DETAILS->MANUFACTURER_NAME) ? $rl->ARTICLE_DETAILS->MANUFACTURER_NAME : '';
        $pro_delivery_time = isset($rl->ARTICLE_DETAILS->DELIVERY_TIME) ? $rl->ARTICLE_DETAILS->DELIVERY_TIME : '';
        $pro_keyword = isset($rl->ARTICLE_DETAILS->KEYWORD) ? $rl->ARTICLE_DETAILS->KEYWORD : ''; //product_keyword
        //$pro_artical_feature = isset($rl->ARTICLE_FEATURES)?$rl->ARTICLE_FEATURES:'';
        $pro_referance_feature_group_id = isset($rl->ARTICLE_FEATURES->REFERENCE_FEATURE_GROUP_ID) ? $rl->ARTICLE_FEATURES->REFERENCE_FEATURE_GROUP_ID : '';
        $pro_feature = isset($rl->ARTICLE_FEATURES->FEATURE) ? $rl->ARTICLE_FEATURES->FEATURE : ''; // product_feature
        $pro_order_unit = isset($rl->ARTICLE_ORDER_DETAILS->ORDER_UNIT) ? $rl->ARTICLE_ORDER_DETAILS->ORDER_UNIT : '';
        $pro_count_unit = isset($rl->ARTICLE_ORDER_DETAILS->CONTENT_UNIT) ? $rl->ARTICLE_ORDER_DETAILS->CONTENT_UNIT : '';
        $pro_no_cu_per_ou = isset($rl->ARTICLE_ORDER_DETAILS->NO_CU_PER_OU) ? $rl->ARTICLE_ORDER_DETAILS->NO_CU_PER_OU : '';
        $pro_price_quantity = isset($rl->ARTICLE_ORDER_DETAILS->PRICE_QUANTITY) ? $rl->ARTICLE_ORDER_DETAILS->PRICE_QUANTITY : '';
        $pro_quantity_min = isset($rl->ARTICLE_ORDER_DETAILS->QUANTITY_MIN) ? $rl->ARTICLE_ORDER_DETAILS->QUANTITY_MIN : '';
        $pro_quantity_interval = isset($rl->ARTICLE_ORDER_DETAILS->QUANTITY_INTERVAL) ? $rl->ARTICLE_ORDER_DETAILS->QUANTITY_INTERVAL : '';
        $pro_artical_price = isset($rl->ARTICLE_PRICE_DETAILS->ARTICLE_PRICE) ? $rl->ARTICLE_PRICE_DETAILS->ARTICLE_PRICE : ''; //product_bundle_price
        $pro_gallery = isset($rl->MIME_INFO->MIME) ? $rl->MIME_INFO->MIME : ''; //product_bundle_price
        /*echo count($pro_gallery);
            print("<pre>");
            print_r($pro_gallery);
            print("pro_price_one = ".$pro_price_one);
            print("</pre>");//die();*/
        //print("supplier_id = ".$supplier_id." pro_ean = ".$pro_ean);die();
        $Query1 = "SELECT * FROM manufacture WHERE  manf_name = '" . dbStr(trim($pro_manufacture_name)) . "'";
        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
        if (mysqli_num_rows($rs1) > 0) {
            $row1 = mysqli_fetch_object($rs1);
            $manf_id = $row1->manf_id;
        } else {
            $manf_id = getMaximum("manufacture", "manf_id");
            mysqli_query($GLOBALS['conn'], "INSERT INTO manufacture (manf_id, manf_name) VALUES ('" . $manf_id . "', '" . dbStr(trim($pro_manufacture_name)) . "')") or die(mysqli_error($GLOBALS['conn']));
        }

        $Query2 = "SELECT * FROM products WHERE  supplier_id = '" . $supplier_id . "'";
        $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
        if (mysqli_num_rows($rs2) > 0) {
            $row2 = mysqli_fetch_object($rs2);
            $pro_uid = $row2->pro_id;
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status = '1', pro_description_short = '" . dbStr(trim($pro_description_short)) . "', pro_description_long = '" . dbStr(trim($pro_description_long)) . "', pro_ean = '" . dbStr(trim($pro_ean)) . "', pro_buyer_id = '" . dbStr(trim($pro_buyer_id)) . "', manf_id = '" . dbStr(trim($manf_id)) . "', pro_delivery_time = '" . dbStr(trim($pro_delivery_time)) . "', pro_order_unit = '" . dbStr(trim($pro_order_unit)) . "', pro_count_unit = '" . dbStr(trim($pro_count_unit)) . "', pro_no_cu_per_ou = '" . dbStr(trim($pro_no_cu_per_ou)) . "', pro_price_quantity = '" . dbStr(trim($pro_price_quantity)) . "', pro_quantity_min = '" . dbStr(trim($pro_quantity_min)) . "', pro_quantity_interval = '" . dbStr(trim($pro_quantity_interval)) . "', pro_updatedby = '" . $_SESSION["UserID"] . "', pro_udate = '" . date_time . "'  WHERE pro_id = '" . $pro_uid . "' ") or die(mysqli_error($GLOBALS['conn']));
        } else {

            mysqli_query($GLOBALS['conn'], "INSERT INTO products (pro_id, supplier_id, pro_description_short, pro_description_long, pro_ean, pro_buyer_id, manf_id, pro_manufacture_aid, pro_delivery_time, pro_order_unit, pro_count_unit, pro_no_cu_per_ou, pro_price_quantity, pro_quantity_min, pro_quantity_interval, pro_addedby, pro_cdate) VALUES ('" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($pro_description_short)) . "', '" . dbStr(trim($pro_description_long)) . "', '" . dbStr(trim($pro_ean)) . "', '" . dbStr(trim($pro_buyer_id)) . "', '" . dbStr(trim($manf_id)) . "', '" . dbStr(trim($pro_manufacture_aid)) . "', '" . dbStr(trim($pro_delivery_time)) . "', '" . dbStr(trim($pro_order_unit)) . "', '" . dbStr(trim($pro_count_unit)) . "', '" . dbStr(trim($pro_no_cu_per_ou)) . "', '" . dbStr(trim($pro_price_quantity)) . "', '" . dbStr(trim($pro_quantity_min)) . "', '" . dbStr(trim($pro_quantity_interval)) . "', '" . $_SESSION["UserID"] . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
        }

        if (!empty($pro_keyword)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM products_keyword WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_keyword); $i++) {
                $pk_title = $pro_keyword[$i];
                $pk_id = getMaximum("products_keyword", "pk_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO products_keyword (pk_id, pro_id, supplier_id, pk_title) VALUES ('" . $pk_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($pk_title)) . "') ") or die(mysqli_error($GLOBALS['conn']));
            }
        }
        if (!empty($pro_feature)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM `products_feature` WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_feature); $i++) {
                $pf_fname = $pro_feature[$i]->FNAME;
                $pf_fvalue = $pro_feature[$i]->FVALUE;
                $pf_forder = $pro_feature[$i]->FORDER;
                $pf_fvalue_details = isset($pro_feature[$i]->FVALUE_DETAILS) ? $pro_feature[$i]->FVALUE_DETAILS : '';
                //print($i.": pf_fname = ".$pf_fname." pf_fvalue = ".$pf_fvalue." pf_forder = ".$pf_forder." pf_fvalue_details = ".$pf_fvalue_details."<br>");
                $pf_id = getMaximum("products_feature", "pf_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO products_feature (pf_id, pro_id, supplier_id, pf_group_id, pf_fname, pf_fvalue, pf_forder, pf_fvalue_details) VALUES ('" . $pf_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . $pro_referance_feature_group_id . "', '" . dbStr(trim($pf_fname)) . "', '" . dbStr(trim($pf_fvalue)) . "', '" . dbStr(trim($pf_forder)) . "', '" . dbStr(trim($pf_fvalue_details)) . "') ") or die(mysqli_error($GLOBALS['conn']));
            }
        }
        if (!empty($pro_artical_price)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM `products_bundle_price` WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_artical_price); $i++) {
                $pbp_price_amount = $pro_artical_price[$i]->PRICE_AMOUNT;
                $pbp_currency = $pro_artical_price[$i]->PRICE_CURRENCY;
                $pbp_tax = $pro_artical_price[$i]->TAX;
                $pbp_lower_bound = $pro_artical_price[$i]->LOWER_BOUND;
                //print($i.": pbp_price_amount = ".$pbp_price_amount." pbp_currency = ".$pbp_currency." pbp_tax = ".$pbp_tax." pbp_lower_bound = ".$pbp_lower_bound."<br>");
                $pbp_id = getMaximum("products_bundle_price", "pbp_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO products_bundle_price (pbp_id, pro_id, supplier_id, pbp_price_amount, pbp_currency, pbp_tax, pbp_lower_bound) VALUES ('" . $pbp_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($pbp_price_amount)) . "', '" . dbStr(trim($pbp_currency)) . "', '" . dbStr(trim($pbp_tax)) . "', '" . dbStr(trim($pbp_lower_bound)) . "') ") or die(mysqli_error($GLOBALS['conn']));
            }
        }
        if (!empty($pro_gallery)) {
            if ($pro_uid > 0) {
                mysqli_query($GLOBALS['conn'], "DELETE FROM `products_gallery` WHERE pro_id = '" . $pro_uid . "' AND supplier_id = '" . $supplier_id . "'") or die(mysqli_error($GLOBALS['conn']));
            }
            for ($i = 0; $i < count($pro_gallery); $i++) {
                $pg_mime_type = $pro_gallery[$i]->MIME_TYPE;
                $pg_mime_source = $pro_gallery[$i]->MIME_SOURCE;
                $pg_mime_description = $pro_gallery[$i]->MIME_DESCR;
                $pg_mime_alt = $pro_gallery[$i]->MIME_ALT;
                $pg_mime_purpose = $pro_gallery[$i]->MIME_PURPOSE;
                $pg_mime_order = $pro_gallery[$i]->MIME_ORDER;
                //print($i.": pg_mime_type = ".$pg_mime_type." pg_mime_source = ".$pg_mime_source." pg_mime_description = ".$pg_mime_description." pg_mime_alt = ".$pg_mime_alt." pg_mime_order =".$pg_mime_order."<br>");
                $pg_id = getMaximum("products_gallery", "pg_id");
                mysqli_query($GLOBALS['conn'], "INSERT INTO products_gallery (pg_id, pro_id, supplier_id, pg_mime_type, pg_mime_source, pg_mime_description, pg_mime_alt, pg_mime_purpose, pg_mime_order) VALUES ('" . $pg_id . "', '" . $pro_id . "', '" . $supplier_id . "', '" . dbStr(trim($pg_mime_type)) . "', '" . dbStr(trim($pg_mime_source)) . "', '" . dbStr(trim($pg_mime_description)) . "', '" . dbStr(trim($pg_mime_alt)) . "', '" . dbStr(trim($pg_mime_purpose)) . "', '" . dbStr(trim($pg_mime_order)) . "') ") or die(mysqli_error($GLOBALS['conn']));
            }
        }

        //die();
        //mysqli_query($GLOBALS['conn'], "INSERT INTO category_map (cat_id, supplier_aid) VALUES ('" . $catalog_group_id . "', '" . $art_id . "')") or die(mysqli_error($GLOBALS['conn']));
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
} elseif (isset($_REQUEST['btnUpdate'])) {
    $dirName = "../files/category/";
    $mfileName = $_REQUEST['mfileName'];
    if (!empty($_FILES["mFile"]["name"])) {
        @unlink("../files/category/" . $_REQUEST['mfileName']);
        @unlink("../files/category/th/" . $_REQUEST['mfileName']);
        $mfileName = $_REQUEST['pro_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "138", "80");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_title_de = '" . dbStr(trim($_REQUEST['cat_title_de'])) . "',  cat_title_en='" . dbStr(trim($_REQUEST['cat_title_en'])) . "', cat_keyword = '" . dbStr(trim($_REQUEST['cat_keyword'])) . "', cat_description = '" . dbStr(trim($_REQUEST['cat_description'])) . "', cat_image='" . $mfileName . "' WHERE pro_id=" . $_REQUEST['pro_id']) or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM category WHERE pro_id = " . $_REQUEST['pro_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $cat_title_en = $rsMem->cat_title_en;
            $cat_title_de = $rsMem->cat_title_de;
            $mfileName = $rsMem->cat_image;
            $mfile_path = !empty($rsMem->cat_image) ? $GLOBALS['siteURL'] . "files/category/" . $rsMem->cat_image : "";
            $cat_keyword = $rsMem->cat_keyword;
            $cat_description = $rsMem->cat_description;
            $formHead = "Update Info";
        }
    } else {
        $cat_title_en = "";
        $cat_title_de = "";
        $mfileName = "";
        $cat_keyword = "";
        $cat_description = "";
        $formHead = "Add New";
    }
}

//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status='1' WHERE pro_id = " . $_REQUEST['chkstatus'][$i]);
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
            mysqli_query($GLOBALS['conn'], "UPDATE products SET pro_status='0' WHERE pro_id = " . $_REQUEST['chkstatus'][$i]);
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
    <div class="container">
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
                <div class="alert alert-success" id="success" style="display: none;"> Record Updated Successfully<a class="close" data-dismiss="alert">×</a></div>
                <div class="alert alert-danger" style="display: none;"> Record not Updated<a class="close" data-dismiss="alert">×</a></div>
                <?php if (isset($_REQUEST['action'])) { ?>
                    <div class="main_container">
                        <h2>
                            <?php print($formHead); ?> Category
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">

                            <?php if ($_REQUEST['action'] == 2) { ?>
                                <div class="input_div">
                                    <img src="<?php print($mfile_path); ?>" width="100%" alt="">
                                </div>
                                <div class="grid_form">
                                    <div class="input_div">
                                        <label for="">Title DE</label>
                                        <input type="text" class="input_style" name="cat_title_de" id="cat_title_de" value="<?php print($cat_title_de); ?>" placeholder="Title">
                                    </div>
                                    <div class="input_div">
                                        <label for="">Title EN</label>
                                        <input type="text" class="input_style" name="cat_title_en" id="cat_title_en" value="<?php print($cat_title_en); ?>" placeholder="Title">
                                    </div>
                                </div>
                                <div class="grid_form">
                                    <div class="input_div">
                                        <label for="">Keywords (Seprate Each Keyword With ',' (Car, Bus, Bike))</label>
                                        <input type="text" class="input_style" name="cat_keyword" id="cat_keyword" value="<?php print($cat_keyword); ?>" placeholder="Keywords (Seprate Each Keyword With ',' (Car, Bus, Bike))">
                                    </div>
                                    <div class="input_div">
                                        <label for="">Meta Description</label>
                                        <input type="text" class="input_style" name="cat_description" id="cat_description" value="<?php print($cat_description); ?>" placeholder="Meta Description">
                                    </div>
                                </div>
                                <div class="grid_form">
                                    <div class="input_div">
                                        <label for="">Image ( <span class="label_span">Banner Size must be 1200px x 300x</span> )</label>
                                        <div class="">
                                            <label for="file-upload" class="upload-btn">
                                                <span class="material-icons">cloud_upload</span>
                                                <span>Upload Files</span>
                                            </label>
                                            <input id="file-upload" type="file" class="file-input" name="mFile">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($_REQUEST['action'] == 2) { ?>
                                <div class="padding_top_bottom">
                                    <button class="add-customer" type="submit" name="btnUpdate" id="btnImport">Update</button>
                                    <input type="hidden" name="mfileName" value="<?php print($mfileName); ?>" />
                                <?php } else { ?>
                                    <div class="text_align_center padding_top_bottom">
                                        <button class="add-customer" type="submit" name="btnImport" id="btnImport">Upload</button>
                                    <?php } ?>
                                    <button type="button" name="btnBack" class="add-customer btn-cancel" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                    </div>

                        </form>
                    </div>
                <?php } else { ?>
                    <div class="main_table_container">
                        <div class="table-controls">


                            <div class="search-box">
                                <label for="">Search</label>
                                <input type="text" class="input_style" placeholder="Search:">
                            </div>
                        </div>

                        <div class="table-controls">
                            <h1>Artical</h1>
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>

                        </div>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="10"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                        <!--<th width="100">Image</th>-->
                                        <th width="100">Artical Id</th>
                                        <th>Title </th>
                                        <th style="text-align: right; width: 256px">Stock</th>
                                        <th style="text-align: right; width: 185px">Price</th>
                                        <th width="50">Status</th>
                                        <th width="110">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //$Query = "SELECT pro.*, pg.pg_mime_source FROM products AS pro LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE 1";
                                    $Query = "SELECT pro.*, pq.pq_id, pq.pq_quantity, pq.pq_upcomming_quantity, pq.pq_status FROM products AS pro LEFT OUTER JOIN products_quantity AS pq ON pq.supplier_id = pro.supplier_id ORDER BY pro.pro_id ASC";
                                    $counter = 0;
                                    $limit = 50;
                                    $start = $p->findStart($limit);
                                    $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                    $pages = $p->findPages($count, $limit);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $counter++;
                                            $strClass = 'label  label-danger';
                                            $image_path = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
                                            /*if (!empty($row->pg_mime_source)) {
                                                $image_path = "../getftpimage.php?img=" . $row->pg_mime_source;
                                            }*/
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->pro_id); ?>"></td>
                                                <!--<td><img src="<?php print($image_path); ?>" width=" <?php print(!empty($row->cat_image) ? 300 : 100); ?>"></td>-->
                                                <td><?php print($row->supplier_id); ?></td>
                                                <td><?php print($row->pro_description_short); ?></td>
                                                <td>
                                                    <div class="table-box-body">
                                                        <input type="hidden" name="pro_id" id="pro_id_<?php print($counter); ?>" value="<?php print($row->pro_id); ?>">
                                                        <input type="hidden" name="supplier_id" id="supplier_id_<?php print($counter); ?>" value="<?php print($row->supplier_id); ?>">
                                                        <input type="hidden" name="pq_id" id="pq_id_<?php print($counter); ?>" value="<?php print($row->pq_id); ?>">
                                                        <div class="table-form-group">
                                                            <label for="">Auf Lager</label>
                                                            <input type="number" name="pq_quantity" id="pq_quantity_<?php print($counter); ?>" value="<?php print($row->pq_quantity); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min = "0" >
                                                        </div>
                                                        <div class="table-form-group">
                                                            <label for="">Online verfügbar</label>
                                                            <input type="number" name="pq_upcomming_quantity" id="pq_upcomming_quantity_<?php print($counter); ?>" value="<?php print($row->pq_upcomming_quantity); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min = "0" >
                                                        </div>
                                                        <div class="table-form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="button" name="pro_update_quantity" data-id="<?php print($counter); ?>" class="btn btn-success btn-style-light pro_update_quantity" value="Update (<?php print(($row->pq_status == "true") ? 'T' : 'F'); ?>)">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-box-body">
                                                        <?php
                                                        $counter1 = 0;
                                                        $Query1 = "SELECT * FROM `products_bundle_price` WHERE pro_id = '" . $row->pro_id . "' AND supplier_id = '" . $row->supplier_id . "' ORDER BY pbp_lower_bound ASC";
                                                        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                                                        if (mysqli_num_rows($rs1) > 0) {
                                                            while ($row1 = mysqli_fetch_object($rs1)) {
                                                                $counter1++;
                                                        ?>
                                                                <div class="table-form-group">
                                                                    <input type="hidden" name="pbp_id" id="pbp_id_<?php print($counter); ?>_<?php print($counter1); ?>" value="<?php print($row1->pbp_id); ?>">
                                                                    <label for="">LB <?php print($row1->pbp_lower_bound) ?> </label>
                                                                    <input type="number" name="pbp_price_amount[]" id="pbp_price_amount_<?php print($counter); ?>_<?php print($counter1); ?>" onkeyup="if(this.value === '' || parseFloat(this.value) <= 0) {this.value = 0;} " min = "0" value="<?php print($row1->pbp_price_amount) ?>">
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <div class="table-form-group">
                                                            <label for="">&nbsp;</label>
                                                            <input type="hidden" name="pro_update_price_lenght" id="pro_update_price_lenght_<?php print($counter); ?>" value="<?php print($counter1); ?>">
                                                            <input type="button" name="btnUpdatePrice" data-id="<?php print($counter); ?>" class="btn btn-success btn-style-light pro_update_price" value="Update">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($row->pro_status == 0) {
                                                        echo '<span class="badge badge-danger">Offline</span>';
                                                    } else {
                                                        echo '<span class="badge badge-success">Live</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pro_id=" . $row->pro_id); ?>';"><span class="material-icons icon material-xs">edit</span></button>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light" title="View" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pro_id=" . $row->pro_id); ?>';"><span class="material-icons icon material-xs">visibility</span></button>
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
                                        <td style="text-align: right;">
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

                            <input type="submit" name="btnActive" value="Active" class="btn btn-primary btn-style-light">
                            <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-style-light">
                            <!--<input type="submit" name="btnDelete" onclick="return confirm('Are you sure you want to delete selected item(s)?');" value="Delete" class="btn btn-danger btn-style-light">-->
                        </form>

                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
    <script>
        $(".pro_update_quantity").on("click", function() {
            //console.log("btnUpdateQuantity");
            let pro_id = $("#pro_id_" + $(this).attr("data-id")).val();
            let supplier_id = $("#supplier_id_" + $(this).attr("data-id")).val();
            let pq_id = $("#pq_id_" + $(this).attr("data-id")).val();
            let pq_quantity = $("#pq_quantity_" + $(this).attr("data-id")).val();
            let pq_upcomming_quantity = $("#pq_upcomming_quantity_" + $(this).attr("data-id")).val();
            //console.log("pro_id: "+pro_id+" supplier_id: "+supplier_id+" pq_id: "+pq_id);
            $.ajax({
                url: 'ajax_calls.php?action=pro_update_quantity',
                method: 'POST',
                data: {
                    pro_id: pro_id,
                    supplier_id: supplier_id,
                    pq_id: pq_id,
                    pq_quantity: pq_quantity,
                    pq_upcomming_quantity: pq_upcomming_quantity
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    //console.log(obj);
                    if (obj.status == 1) {
                        $("#pq_quantity_" + $(this).attr("data-id")).val(obj.data[0].pq_quantity);
                        $("#pq_upcomming_quantity_" + $(this).attr("data-id")).val(obj.data[0].pq_upcomming_quantity);
                        $("#success").show();
                        setTimeout(function() {
                            $("#success").hide();
                        }, 800);
                    }
                }
            });
        });
        $(".pro_update_price").on("click", function() {
            //console.log("btnUpdateQuantity");
            let priceData = [];
            let pro_id = $("#pro_id_" + $(this).attr("data-id")).val();
            let supplier_id = $("#supplier_id_" + $(this).attr("data-id")).val();
            let pro_update_price_lenght = $("#pro_update_price_lenght_" + $(this).attr("data-id")).val();
            for (let i = 1; i <= pro_update_price_lenght; i++) {
                //console.log("i: "+i);
                let pbp_id = $("#pbp_id_" + $(this).attr("data-id")+"_"+i).val();
                let pbp_price_amount = $("#pbp_price_amount_" + $(this).attr("data-id")+"_"+i).val();
                priceData.push({
                    pbp_id: pbp_id,
                    pbp_price_amount: pbp_price_amount
                });
            }
            //let pbp_id = $("#pbp_id_" + $(this).attr("data-id")).val();
            //let pbp_price_amount = $("#pbp_price_amount_" + $(this).attr("data-id")).val();
            //console.log("pro_id: "+pro_id+" supplier_id: "+supplier_id+" pbp_id: "+pbp_id+" pbp_price_amount: "+pbp_price_amount);
            $.ajax({
                url: 'ajax_calls.php?action=pro_update_price',
                method: 'POST',
                data: {
                    pro_id: pro_id,
                    supplier_id: supplier_id,
                    priceData: priceData
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    console.log(obj);
                    if (obj.status == 1) {
                        $("#success").show();
                        setTimeout(function() {
                            $("#success").hide();
                        }, 800);
                    }
                }
            });
        });
    </script>
</body>

</html>