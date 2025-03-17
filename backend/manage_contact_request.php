<?php
include("../lib/session_head.php");

if (isset($_REQUEST['cu_is_viewed'])) {
    mysqli_query($GLOBALS['conn'], "UPDATE contact_us_request SET cu_is_viewed = '".$_REQUEST['cu_is_viewed']."' WHERE cu_id = '" . $_REQUEST['cu_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?cu_id=" . $_REQUEST['cu_id'] . "&op=2");
} elseif (isset($_REQUEST['btnDelete'])) {
    mysqli_query($GLOBALS['conn'], "DELETE FROM `contact_us_request` WHERE cu_id = '" . $_REQUEST['cu_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
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
                        Kontakt Formular
                    </h2>
                    <div class="row mt-3 position-relative">
                        <div class="col-md-3 col-12 bg-secondary rounded-3">
                            <?php
                            $Query1 = "SELECT * FROM contact_us_request ORDER BY cu_date DESC";
                            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                            if (mysqli_num_rows($rs1)) {
                                while ($row1 = mysqli_fetch_object($rs1)) {
                            ?>
                                    <a href="<?php print($_SERVER['PHP_SELF'] . "?cu_id=" . $row1->cu_id); ?>" class="tab_container p-3 border-bottom">
                                        <div class="tab_img rounded">
                                            <img src="../images/user_img.png" alt="">
                                        </div>
                                        <div class="tab_detail">
                                            <div class="user_detail">
                                                <?php
                                                print($row1->cu_name);
                                                if ($row1->cu_is_viewed > 0) {
                                                    print('<span class="ms-2 p-2 mb-3 text-bg-success rounded-3"> Close</span>');
                                                } else {
                                                    print('<span class="ms-2 p-2 mb-3 text-bg-danger rounded-3"> Open</span>');
                                                }
                                                ?>
                                            </div>
                                            <div class="contact_date">
                                                <?php print(date('F j, Y H:i', strtotime($row1->cu_date))); ?>
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
                            $searchQuery = "ORDER BY cu_id DESC LIMIT 0,1";
                            if (isset($_REQUEST['cu_id']) && $_REQUEST['cu_id'] > 0) {
                                $searchQuery = "WHERE cu_id = '" . $_REQUEST['cu_id'] . "'";
                            }
                            $Query2 = "SELECT * FROM contact_us_request " . $searchQuery . "";
                            $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                            if (mysqli_num_rows($rs2) > 0) {
                                $row2 = mysqli_fetch_object($rs2);
                            ?>
                                <div class="contact_detail position-sticky">
                                    <div class="contact_user_info border-bottom pb-3">
                                        <div class="contact_date">
                                            <?php print(date('F j, Y H:i', strtotime($row2->cu_date))); ?>
                                        </div>
                                        <h3 class="from text-white">
                                            From: <?php  print($row2->cu_name); ?>
                                        </h3>
                                        <div class="contact_detail_user_info">
                                            <div class="tab_img rounded">
                                                <img src="../images/user_img.png" alt="">
                                            </div>
                                            <div class="user_info_detail">
                                                <div class="user_detail">
                                                    <?php print($row2->cu_email); ?>
                                                </div>
                                                <div class="contact_date">
                                                    To: <span class="ms-1 p-1 mb-3 text-bg-info rounded-3 text-white"> Wacker</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contact_message border-bottom pb-3">
                                        <?php print($row2->cu_message); ?>
                                    </div>
                                    <div class="contact_name border-bottom pb-3 fs-5 text-white">
                                        Name: <?php print($row2->cu_name); ?>
                                    </div>
                                    <div class="contact_email border-bottom pb-3 fs-5 text-white">
                                        Email: <?php print($row2->cu_email); ?>
                                    </div>
                                    <div class="contact_phone border-bottom pb-3 fs-5 text-white">
                                        Phone: <?php print($row2->cu_phone); ?>
                                    </div>
                                    <div class="contact_topic border-bottom pb-3 fs-5 text-white">
                                        Topic: <?php print($row2->cu_subject); ?>
                                    </div>
                                    <div class="bottom_btndelete text-end">
                                        <?php if ($row2->cu_is_viewed > 0) { ?>
                                            <a href="<?php print($_SERVER['PHP_SELF'] . "?cu_is_viewed=0&cu_id=" . $row2->cu_id); ?>" class="btn btn-danger btn-style-light w-auto">Open</a>
                                        <?php } else { ?>
                                            <a href="<?php print($_SERVER['PHP_SELF'] . "?cu_is_viewed=1&cu_id=" . $row2->cu_id); ?>" class="btn btn-success btn-style-light w-auto">Close</a>
                                        <?php } ?>
                                        <a href="<?php print($_SERVER['PHP_SELF'] . "?btnDelete&cu_id=" . $row2->cu_id); ?>" class="btn btn-danger btn-style-light w-auto">Delete</a>
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