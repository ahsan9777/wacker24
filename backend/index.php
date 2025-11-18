<?php
include("../lib/session_head.php");
$ord_id = 0;
$ord_id = getMaximum("orders", "ord_id");
if ($ord_id > 0) {
    $ord_id = $ord_id - 1;
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
    <style>
        .cart {
            background-color: #1f1f2b;
            border: none;
            border-radius: 10px;
        }

        .cart_body {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 10px;
        }

        .cart_icon {
            border-radius: 10px;
            width: 60px;
            height: 60px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart_text label {
            text-transform: uppercase;
            font-weight: 500;
            color: #a1a5b5;
            font-size: 13px;
        }

        .cart_text h2 {
            color: #c2c4d1;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .cart_text .cart_text_right p {
            background: red;
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 5px;
            margin: 0px;
        }

        .col-md-2-half {
            max-width: 20.2%;
        }

        @media screen and (max-width: 1024px) and (min-width: 240px) {
            .col-md-2-half {
                max-width: 100%;
            }
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

                <h2 class="text-white text-start pt-3 pb-3">
                    Backend Control Panel
                </h2>
                <div class="row mt-3 column-gap-4">
                    <div class="col-md-2-half col-12 mt-3 cart ">
                        <a class="text-decoration-none" href="manage_products.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-success btn-style-light">
                                    <i class="material-icons icon fs-1">shopping_cart</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Artikel</label>
                                        <h2><?php print(TotalRecords("pro_id", "products", "WHERE 1 = 1")); ?></h2>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <audio id="bellSound" src="assets/audio/order.mp3" preload="auto"></audio>
                        <input type="hidden" name="ord_id" id="ord_id" value="<?php print($ord_id); ?>">
                        <a class="text-decoration-none" href="manage_orders.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-success btn-style-light">
                                    <i class="material-icons icon fs-1">inventory</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Bestellungen</label>
                                        <h2 id="total_no_of_order"><?php print(TotalRecords("ord_id", "orders", "WHERE 1 = 1")); ?></h2>
                                    </div>
                                    <?php $order_pending_count = TotalRecords("ord_id", "orders", "WHERE ord_delivery_status = '0' ");
                                    if ($order_pending_count > 0) {
                                    ?>
                                        <a href="manage_orders.php" class="cart_text_right text-decoration-none" id = "pending_order_srarus_one">
                                            <p> <?php print($order_pending_count); ?> ausstehend</p>
                                        </a>
                                    <?php } ?>
                                    <a href="manage_orders.php" class="cart_text_right text-decoration-none" id = "pending_order_srarus_two" style="display: none;">
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart ">
                        <a class="text-decoration-none" href="manage_products.php?pro_status=1">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-success btn-style-light">
                                    <i class="material-icons icon fs-1">shopping_cart</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Live Artikel</label>
                                        <h2><?php print(TotalRecords("pro_id", "products", "WHERE pro_status = '1'")); ?></h2>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart ">
                        <a class="text-decoration-none" href="manage_products.php?pro_status=2">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-danger btn-style-light">
                                    <i class="material-symbols-outlined icon fs-1">shopping_cart_off</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Offline Artikel</label>
                                        <h2><?php print(TotalRecords("pro_id", "products", "WHERE pro_status = '0'")); ?></h2>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_users.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-1">face</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Benutzer</label>
                                        <h2><?php print(TotalRecords("user_id", "users", "WHERE 1 = 1")); ?></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_users.php?utype_id=3">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-1">person</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Privatkunde</label>
                                        <h2><?php print(TotalRecords("user_id", "users", "WHERE utype_id = '3'")); ?></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_users.php?utype_id=4">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-1">business</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Geschäftskunde</label>
                                        <h2><?php print(TotalRecords("user_id", "users", "WHERE utype_id = '4'")); ?></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-1">sell</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Artikeltypen</label>
                                        <h2>0</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_category.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-1">category</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Hauptkategorien</label>
                                        <h2><?php print(TotalRecords("cat_id", "category", "WHERE parent_id = '0'")); ?></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_sub_category.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-1">category</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Unterkategorien</label>
                                        <h2><?php print(TotalRecords("cat_id", "category", "WHERE parent_id > '0'")); ?></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-1">alternate_email</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Newsletters</label>
                                        <h2>0</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_brands.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-1">star</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Marken</label>
                                        <h2><?php print(TotalRecords("brand_id", "brands", "WHERE 1 = 1")); ?></h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-5 col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_appointment.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-success btn-style-light">
                                    <i class="material-icons icon fs-1">calendar_month</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Terminvereinbarungs</label>
                                        <h2><?php print(TotalRecords("app_id", "appointments", "WHERE 1 = 1")); ?></h2>
                                    </div>
                                    <?php $appointments_count = TotalRecords("app_id", "appointments", "WHERE app_status = '0' ");
                                    if ($appointments_count > 0) {
                                    ?>
                                        <a href="manage_appointment.php" class="cart_text_right text-decoration-none">
                                            <p> <?php print($appointments_count); ?> ausstehend</p>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-5 col-12 mt-3 cart">
                        <a class="text-decoration-none" href="manage_contact_request.php">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-danger btn-style-light">
                                    <i class="material-icons icon fs-1">mail</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Kontakt Formular</label>
                                        <h2><?php print(TotalRecords("cu_id", "contact_us_request", "WHERE 1 = 1")); ?></h2>
                                    </div>
                                    <?php $contact_us_request_pending_count = TotalRecords("cu_id", "contact_us_request", "WHERE cu_is_viewed = '0' ");
                                    if ($contact_us_request_pending_count > 0) {
                                    ?>
                                        <a href="manage_contact_request.php" class="cart_text_right text-decoration-none">
                                            <p> <?php print($contact_us_request_pending_count); ?> ausstehend</p>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                    </div>
                    </a>
                </div>
        </div>

        </section>
    </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>
<script>
    function fetchData() {
        let ord_id = $("#ord_id").val();
        //console.log("ord_id: ", ord_id);
        //document.getElementById("bellSound").play();
        $.ajax({
            url: "ajax_calls.php?action=fetch_new_order_data",
            type: "POST", // or POST
            data: {
                ord_id: ord_id
            },
            success: function(response) {
                //console.log("AJAX Response:", response);
                const obj = JSON.parse(response);
                //console.log(obj);
                
                if(obj.status == 1){
                    document.getElementById("bellSound").play();
                    $("#ord_id").val(obj.ord_id_new);
                    $("#pending_order_srarus_one").hide();
                    $("#pending_order_srarus_two").show();
                    $("#total_no_of_order").text(obj.total_no_of_order);
                    $("#pending_order_srarus_two").html("<p>"+obj.order_pending_count+" ausstehend</p>");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    } // 300000 ms = 5 minutes

    fetchData();
    setInterval(fetchData, 6000);
</script>

</html>