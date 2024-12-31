<?php
include("../lib/session_head.php");

if (isset($_REQUEST['btnImport'])) {
    //print_r($_REQUEST);die();
    $xml = simplexml_load_file("lagersortiment_standard.xml") or die("Error: Cannot create object");
    /*print('<pre>');
    print_r($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM);
    print('</pre>');
      $i = 0;
    foreach ($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM as $rl) {
            echo $i++." ".$rl->CATALOG_STRUCTURE->GROUP_NAME.PHP_EOL;
    }
    echo count($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE);*/

    for ($i = 1; $i < count($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE); $i++) {
        $cat_id = getMaximum("category", "cat_id");
        $group_id = $xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE[$i]->GROUP_ID;
        $group_name = $xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE[$i]->GROUP_NAME;
        $parent_id = $xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM->CATALOG_STRUCTURE[$i]->PARENT_ID;
        if ($parent_id == 1) {
            $parent_id = 0;
        }
        //print("group_id = ".$group_id."<br>group_name = ".$group_name."<br> parent_id = ".$parent_id."<br><br>");
        $Query = "SELECT * FROM category WHERE  cat_title_de = '" . dbStr(trim($group_name)) . "' AND group_id = '".dbStr(trim($group_id))."' AND parent_id = '".dbStr(trim($parent_id))."'";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $row = mysqli_fetch_object($rs);
            $cat_id = $row->cat_id;
            mysqli_query($GLOBALS['conn'], "UPDATE category SET cat_title_de = '" . dbStr(trim($group_name)) . "', cat_params_de = '" . url_clean($group_name) . "' WHERE cat_id = '".$cat_id."'") or die(mysqli_error($GLOBALS['conn']));
        } else {
            mysqli_query($GLOBALS['conn'], "INSERT INTO category (cat_id, group_id, parent_id, cat_title_de, cat_params_de) VALUES ('" . $cat_id . "', '" . $group_id . "', '" . $parent_id . "', '" . dbStr(trim($group_name)) . "', '" . url_clean($group_name) . "')") or die(mysqli_error($GLOBALS['conn']));
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
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
                <?php if (isset($_REQUEST['action'])) { ?>
                    <div class="main_container">
                        <h2>
                            Add New Customer
                        </h2>
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">

                            <div class="text_align_center padding_top_bottom">
                                <button class="add-customer" type="submit" name="btnImport" id="btnImport" >Upload</button>
                                <button type="button" name="btnBack" class="add-customer btn-cancel" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                            </div>

                        </form>
                    </div>
                <?php } else { ?>
                    <div class="main_table_container">
                        <div class="table-controls">


                            <div class="search-box">
                                <label for="">Customer Type</label>
                                <input type="text" class="input_style" placeholder="Search:">
                            </div>
                        </div>

                        <div class="table-controls">
                            <h1>Customers</h1>
                            <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="add-new"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>

                        </div>
                        <div class="table_responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox"></th>
                                        <th>customer number</th>
                                        <th>Full Name</th>
                                        <th>E-mail</th>
                                        <th>zip code</th>
                                        <th>Street</th>
                                        <th>type</th>
                                        <th>Created</th>
                                        <th>type</th>
                                        <th>Created</th>
                                        <th>type</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>1</td>
                                        <td>sayed Ka</td>
                                        <td>red25558@gmail.com</td>
                                        <td></td>
                                        <td></td>
                                        <td><span class="badge business">Business</span></td>
                                        <td>2024.10.31<br>09:00:24</td>
                                        <td><span class="badge business">Business</span></td>
                                        <td>2024.10.31<br>09:00:24</td>
                                        <td><span class="badge business">Business</span></td>
                                        <td>2024.10.31<br>09:00:24</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>

</html>