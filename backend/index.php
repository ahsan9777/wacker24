<?php include("../lib/session_head.php"); ?>
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
            padding: 25px 30px;
        }

        .cart_icon {
            border-radius: 10px;
            width: 60px;
            height: 60px;
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
            font-size: 14px;
            font-weight: 700;
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
                    <div class="col-md-5 col-12 mt-3 cart ">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-success btn-style-light">
                                    <i class="material-icons icon fs-3">shopping_cart</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Artikel</label>
                                        <h2>35665</h2>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-md-5 col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-success btn-style-light">
                                    <i class="material-icons icon fs-3">inventory</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Bestellungen</label>
                                        <h2>181</h2>
                                    </div>
                                    <div class="cart_text_right">
                                        <p>2 ausstehend</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-3">face</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Benutzer</label>
                                        <h2>103</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-3">person</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Privatkunde</label>
                                        <h2>17</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-3">business</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Geschäftskunde</label>
                                        <h2>86</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-primary btn-style-light">
                                    <i class="material-icons icon fs-3">category</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Hauptkategorien</label>
                                        <h2>10</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-3">category</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Unterkategorien</label>
                                        <h2>115</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2-half col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-3">sell</i>
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
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-3">alternate_email</i>
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
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-warning btn-style-light">
                                    <i class="material-icons icon fs-3">star</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Marken</label>
                                        <h2>150</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-5 col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-success btn-style-light">
                                    <i class="material-icons icon fs-3">calendar_month</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Terminvereinbarungs</label>
                                        <h2>5</h2>
                                    </div>
                                    <div class="cart_text_right">
                                        <p>2 ausstehend</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-5 col-12 mt-3 cart">
                        <a class="text-decoration-none" href="javascript:void(0)">
                            <div class="cart_body">
                                <div class="cart_icon btn btn-xs btn-danger btn-style-light">
                                    <i class="material-icons icon fs-3">mail</i>
                                </div>
                                <div class="cart_text w-100 d-flex justify-content-between align-items-center">
                                    <div class="cart_text_left">
                                        <label for="">Kontakt Formular</label>
                                        <h2>4</h2>
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

</html>