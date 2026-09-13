<?php
include("../lib/session_head.php");

$orid_id = $_REQUEST['orid_id'];
$orid_files = "";
$Query = "SELECT * FROM `order_return_item_detail` WHERE orid_id = '".$orid_id."'";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if(mysqli_num_rows($rs) > 0){
    $row = mysqli_fetch_object($rs);
    $orid_files = $row->orid_files;
}

$uploadPath = $GLOBALS['siteURL'] . "files/order_return/";

$fileArray = array_filter(array_map('trim', explode(',', $orid_files)));

$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$videoExtensions = ['mp4', 'webm', 'ogg'];

$images = [];
$videos = [];

// Separate images and videos
foreach ($fileArray as $file) {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (in_array($extension, $imageExtensions)) {
        $images[] = $file;
    } elseif (in_array($extension, $videoExtensions)) {
        $videos[] = $file;
    }
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
    <style>
        .main_table_container{
            border-radius:15px;
            padding:25px;
        }

        .media-card{
            background:#fff;
            border-radius:12px;
            overflow:hidden;
            box-shadow:0 5px 15px rgba(0,0,0,.08);
            transition:.3s;
            cursor:pointer;
        }

        .media-card:hover{
            transform:translateY(-6px);
            box-shadow:0 10px 25px rgba(0,0,0,.18);
        }

        .media-card img,
        .media-card video{
            width:100%;
            height:220px;
            object-fit:cover;
            display:block;
        }

        hr{
            opacity:.15;
        }
    </style>
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
                <div class="table-controls">
                    <h1 class="text-white">Order Return Attachments</h1>
                </div>

                <div class="main_table_container">

                    <?php if (!empty($images)) { ?>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-icons me-2 text-primary">photo_library</span>
                            <h4 class="mb-0">Images (<?= count($images) ?>)</h4>
                        </div>

                        <div class="row g-4">
                            <?php foreach ($images as $file) { ?>
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <div class="media-card">
                                        <div class="popup_container" style="width: 100%;">
                                            <div class="container__img-holder">
                                                <img src="<?= $uploadPath . $file; ?>" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>


                    <?php if (!empty($videos)) { ?>

                        <hr class="my-5">

                        <div class="d-flex align-items-center mb-3">
                            <span class="material-icons me-2 text-danger">videocam</span>
                            <h4 class="mb-0">Videos (<?= count($videos) ?>)</h4>
                        </div>

                        <div class="row g-4">
                            <?php foreach ($videos as $file) {
                                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            ?>

                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="media-card">
                                        <video controls>
                                            <source src="<?= $uploadPath . $file; ?>" type="video/<?= $extension; ?>">
                                        </video>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>

                    <?php } ?>

                </div>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>

</html>