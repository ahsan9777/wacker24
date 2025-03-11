<?php
include("../lib/session_head.php");
$formHead = "Add New";


//$searchQuery = "WHERE 1 = 1";
if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
    $user_id = $_REQUEST['user_id'];
    $qryStrURL = "user_id=" . $user_id . "&";
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
                <div class="table-controls">
                    <h1 class="text-white"> User Wish List </h1>
                    <!--<a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" class="btn btn-primary d-flex gap-2"><span class="material-icons icon">add</span> <span class="text">Add New</span></a>-->

                </div>
                <div class="main_table_container">
                    <?php

                    $sl_id = 0;
                    $searchQuery = "";
                    if (isset($_REQUEST['sl_id']) && $_REQUEST['sl_id'] > 0) {
                        $sl_id = $_REQUEST['sl_id'];
                        $searchQuery = " AND wl.sl_id = '" . $_REQUEST['sl_id'] . "'";
                    }
                    ?>

                    <form class="row flex-row" name="frm_search" id="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                        <div class=" col-md-2 col-12 mt-2">
                            <label for="" class="text-white">List</label>
                            <select name="sl_id" id="sl_id" class="input_style" onchange="javascript: frm_search.submit();">
                                <option value="0">N/A</option>
                                <?php FillSelected2("shopping_list", "sl_id", "sl_title", $sl_id, "user_id = '" . $user_id . "'"); ?>
                            </select>
                        </div>
                    </form>
                    <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <table>
                            <thead>
                                <tr>
                                    <th width="200">Image </th>
                                    <th>List </th>
                                    <th>Title </th>
                                    <th width="50">Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query = "SELECT wl.*, sl.sl_title, pro.pro_description_short, pg.pg_mime_source_url FROM wishlist AS wl LEFT OUTER JOIN shopping_list AS sl ON sl.sl_id = wl.sl_id INNER JOIN products AS pro ON pro.supplier_id = wl.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE wl.user_id = '" . $user_id . "' " . $searchQuery . " ORDER BY sl.sl_id ASC ";
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
                                        //echo $row->pg_mime_source_url;
                                ?>
                                        <tr>
                                            <td>
                                                <div class="popup_container">
                                                    <div class="container__img-holder">
                                                        <img src="<?php print(get_image_link(427, $row->pg_mime_source_url)); ?>">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php print($row->sl_title); ?></td>
                                            <td><?php print($row->pro_description_short); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL'] . "product_detail.php?supplier_id=" . $row->supplier_id); ?>');"><span class="material-icons icon material-xs">visibility</span></button>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    print('<tr><td colspan="100%" class="text-center">No record found!</td></tr>');
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
                    </form>

                </div>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>

</html>