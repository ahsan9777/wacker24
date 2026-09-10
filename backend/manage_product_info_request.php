<?php
include("../lib/session_head.php");

if (isset($_REQUEST['pis_status'])) {
    mysqli_query($GLOBALS['conn'], "UPDATE products_info_submit SET pis_status = '".$_REQUEST['pis_status']."' WHERE pis_id = '" . $_REQUEST['pis_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?pis_id=" . $_REQUEST['pis_id'] . "&op=2");
} elseif (isset($_REQUEST['btnDelete'])) {
    mysqli_query($GLOBALS['conn'], "DELETE FROM `products_info_submit` WHERE pis_id = '" . $_REQUEST['pis_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?op=5");
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
                <div class="container text-start">
                    <?php if ($class != "") { ?>
                        <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                    <?php } ?>
                    <h2 class="text-white">
                        Artical Info Request
                    </h2>
                    <div class="row mt-3 position-relative">
                        <div class="col-md-3 col-12 bg-secondary rounded-3">
                            <?php
                            $Query1 = "SELECT pis.*, u.user_fname FROM products_info_submit AS pis LEFT OUTER JOIN users AS u ON u.user_id = pis.user_id ORDER BY pis.pis_cdate DESC";
                            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                            if (mysqli_num_rows($rs1)) {
                                while ($row1 = mysqli_fetch_object($rs1)) {
                            ?>
                                    <a href="<?php print($_SERVER['PHP_SELF'] . "?pis_id=" . $row1->pis_id); ?>" class="tab_container p-3 border-bottom">
                                        <div class="tab_img rounded">
                                            <img src="../images/user_img.png" alt="">
                                        </div>
                                        <div class="tab_detail">
                                            <div class="user_detail">
                                                <?php
                                                print($row1->user_fname);
                                                if ($row1->pis_status > 0) {
                                                    print('<span class="ms-2 p-2 mb-3 text-bg-success rounded-3"> Close</span>');
                                                } else {
                                                    print('<span class="ms-2 p-2 mb-3 text-bg-danger rounded-3"> Open</span>');
                                                }
                                                ?>
                                            </div>
                                            <div class="contact_date">
                                                <?php print(date('F j, Y H:i', strtotime($row1->pis_cdate))); ?>
                                            </div>
                                        </div>
                                    </a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="col-md-9 col-12 bg-dark rounded-3 p-3">
                            <?php
                            $searchQuery = "ORDER BY pis.pis_id DESC LIMIT 0,1";
                            if (isset($_REQUEST['pis_id']) && $_REQUEST['pis_id'] > 0) {
                                $searchQuery = "WHERE pis.pis_id = '" . $_REQUEST['pis_id'] . "'";
                            }
                            $Query2 = "SELECT pis.*, u.user_name, u.user_fname, CONCAT(u.user_fname,' ', u.user_lname) AS user_full_name, u.user_phone FROM products_info_submit AS pis LEFT OUTER JOIN users AS u ON u.user_id = pis.user_id " . $searchQuery . "";
                            $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                            if (mysqli_num_rows($rs2) > 0) {
                                $row2 = mysqli_fetch_object($rs2);
                            ?>
                                <div class="contact_detail position-sticky">
                                    <div class="contact_user_info border-bottom pb-3">
                                        <div class="contact_date">
                                            <?php print(date('F j, Y H:i', strtotime($row2->pis_cdate))); ?>
                                        </div>
                                        <h3 class="from text-white">
                                            From: <?php  print($row2->user_fname); ?>
                                        </h3>
                                        <div class="contact_detail_user_info">
                                            <div class="tab_img rounded">
                                                <img src="../images/user_img.png" alt="">
                                            </div>
                                            <div class="user_info_detail">
                                                <div class="user_detail">
                                                    <?php print($row2->user_name); ?>
                                                </div>
                                                <div class="contact_date">
                                                    To: <span class="ms-1 p-1 mb-3 text-bg-info rounded-3 text-white"> Wacker</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contact_name border-bottom pb-3 fs-5 text-white">
                                        Name: <?php print($row2->user_full_name); ?>
                                    </div>
                                    <div class="contact_email border-bottom pb-3 fs-5 text-white">
                                        Email: <?php print($row2->user_name); ?>
                                    </div>
                                    <div class="contact_phone border-bottom pb-3 fs-5 text-white">
                                        Telefon: <?php print($row2->user_phone); ?>
                                    </div>
                                    <div class="main_table_container">
                                        <form class="table_responsive" name="frm_table_detail" id="frm_table_detail" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th width="100">Bild</th>
                                                        <th>Lieferanten-ID</th>
                                                        <th>Titel</th>
                                                        <th width = "70">Aktion</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                $Query3 = "SELECT pro.*, pg.pg_mime_source_url FROM products AS pro LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) WHERE pro.pro_id = '".$row2->pro_id."'";
                                                //print($Query);
                                                $rs3 = mysqli_query($GLOBALS['conn'], $Query3);
                                                if (mysqli_num_rows($rs3) > 0) {
                                                    $row3 = mysqli_fetch_object($rs3);
                                                ?>
                                                    <tr>
                                                        <td width="100">
                                                            <div class="popup_container">
                                                                <div class="container__img-holder">
                                                                    <img src="<?php print(get_image_link(160, $row3->pg_mime_source_url)); ?>">
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?php print($row3->supplier_id); ?></td>
                                                        <td><?php print($row3->pro_description_short); ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL'].product_detail_url($row3->supplier_id)); ?>');"><span class="material-icons icon material-xs">visibility</span></button>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="contact_message border-bottom pb-3">
                                        <?php print($row2->pis_info_detail); ?>
                                    </div>
                                    <div class="bottom_btndelete text-end">
                                        <?php if ($row2->pis_status > 0) { ?>
                                            <a href="<?php print($_SERVER['PHP_SELF'] . "?pis_status=0&pis_id=" . $row2->pis_id); ?>" class="btn btn-danger btn-style-light w-auto">Open</a>
                                        <?php } else { ?>
                                            <a href="<?php print($_SERVER['PHP_SELF'] . "?pis_status=1&pis_id=" . $row2->pis_id); ?>" class="btn btn-success btn-style-light w-auto">Close</a>
                                        <?php } ?>
                                        <a href="<?php print($_SERVER['PHP_SELF'] . "?btnDelete&pis_id=" . $row2->pis_id); ?>" class="btn btn-danger btn-style-light w-auto">Delete</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>
</html>