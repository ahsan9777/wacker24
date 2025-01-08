<?php
include("includes/php_includes_top.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'brand':
            die();
            $Query = "SELECT * FROM wacker_brand ORDER BY id ASC";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $brand_name = $row->brand_name;
                    $brand_logo = str_replace(" ", "-", strtolower(basename($row->brand_logo)));
                    $extension = pathinfo($brand_logo, PATHINFO_EXTENSION);
                    $brand_logo = str_replace(" ", "-", strtolower($row->brand_name)).".".$extension;
                    $brand_category = $row->brand_category;
                    //$oldname = "files/brands/".basename($row->brand_logo);
                    //$newname = "files/brands/".str_replace(" ", "-", strtolower($row->brand_name)).".".$extension;

                    /*if (rename($oldname, $newname)) {
                        echo "File renamed successfully.";
                    } else {
                        echo "File renaming failed.";
                    }*/
                    $brand_id = getMaximum("brands", "brand_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO brands (brand_id, cat_id, brand_name, brand_image) VALUES ('".$brand_id."', '".$brand_category."', '".$brand_name."', '".$brand_logo."')") or die(mysqli_error($GLOBALS['conn']));
                    print("extension: ".$extension." brand_name: ".$brand_name ." brand_logo: ".$brand_logo."<br>");
                }
            }
            break;
    }
}
