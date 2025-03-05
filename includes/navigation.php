<?php
$background_color_one = "";
$background_color_two = "";
$pbp_price_with_tex_display = "";
$price_without_tex_display = "";
$switch_click_check = "";
$display_check = 'style="display: none;"';
if (isset($_SESSION["utype_id"]) && $_SESSION['utype_id'] == 4) {
    $background_color_one = 'style="background-color: ' . config_company_color_a . ';"';
    $background_color_two = 'style="background-color: ' . config_company_color_b . ';"';
    $switch_click_check = "checked";
    $price_without_tex_display = 'style="display: block;"';
    $pbp_price_with_tex_display = 'style="display: none;"';
    $display_check = '';
}
//print($_SESSION["FullName"]);
?>
<header id="header_section" class="header_sticky">
    <div class="header_top" <?php print($background_color_one); ?>>
        <ul>
            <li>
                <div class="gerenric_switch">
                    <span>Private Customer</span>
                    <span>
                        <input class="switch_click" type="checkbox" hidden="hidden" id="username" <?php print($switch_click_check); ?>>
                        <label class="switch" for="username"></label>
                    </span>
                </div>
            </li>
            <li>Business Customer</li>
        </ul>
        <ul>
            <li><a href="javascript:void(0)">About Wacker 24</a></li>
            <li><a href="contact_us.php">Contact</a></li>
            <li>
                <div class="header_language">
                    <div class="language_select"><a href="javascript:void(0)"><img src="images/gm_icon.png" alt=""> Germany <i class="fa fa-caret-down" aria-hidden="true"></i></a></div>
                    <ul>
                        <li><a href="javascript:void(0)"><img src="images/en_icon.svg" alt=""> English</a></li>
                        <li><a href="javascript:void(0)"><img src="images/gm_icon.png" alt=""> Germany</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <div class="header_sticky">
        <div class="header_bottom" <?php print($background_color_two); ?>>
            <div id="logo"><a href="<?php print($GLOBALS['siteURL']); ?>"><img src="<?php print(config_site_logo) ?>" alt=""></a></div>
            <div class="header_location location_trigger"><i class="fa fa-map-marker" aria-hidden="true"></i> Versand <span> <?php print((isset($_SESSION['ort']) && !empty($_SESSION['ort']))? $_SESSION['ort'] : 'Germany'); ?> </span></div>
            <form class="header_search" name="frm_search" id="frm_search" method="post" action="search_result.php" role="form" enctype="multipart/form-data">
                <div class="header_select">
                    <select class="header_select_slt" name="level_one" id="level_one">
                        <option value="0">ALL</option>
                        <?php FillSelected2("category", "group_id", "cat_title_de AS cat_title", $cat_id, "cat_status = '1' AND parent_id = '0'"); ?>
                    </select>
                </div>
                <input type="hidden" name="supplier_id" id="supplier_id" value="0">
                <input type="text" class="search_input search_keyword" name="search_keyword" id="search_keyword" value="<?php print($search_keyword); ?>" placeholder="Suchhbegriff" autocomplete="off">
                <button class="search_icon"></button>
            </form>
            <div class="header_account">
                <ul>
                    <li><a href="appointments.php">
                            <div class="appointment_booking"><i class="fa fa-check-square-o" aria-hidden="true"></i> appointment booking</div>
                        </a></li>
                    <li>
                        <div class="hdr_icon"><i class="fa fa-user" aria-hidden="true"></i> </div>
                        <div class="hdr_text">
                            <a href="javascript:void(0)"><span> <?php print(isset($_SESSION["FullName"]) ? "Hi, " . $_SESSION["FullName"] : "Hello Login"); ?> <?php if( (isset($_SESSION["utype_id"]) && in_array($_SESSION["utype_id"], array(3,4))) || !isset($_SESSION["FullName"])){ ?> </span>Account & List <i class="fa fa-caret-down"></i> <?php } ?> </a>
                            <?php if( (isset($_SESSION["utype_id"]) && in_array($_SESSION["utype_id"], array(3,4))) || !isset($_SESSION["FullName"])){ ?>
                            <div class="account_nav">
                                <ul>
                                    <li>
                                        <?php if (!isset($_SESSION["FullName"])) { ?>
                                            <div class="full_width txt_align_center mb-10"><a class="href_login" href="login.php" >
                                                    <div class="gerenric_btn">Login</div>
                                                </a></div>
                                            <div class="full_width txt_align_center">New Account? <a href="registration.php"><b>Create One here</b></a></div>
                                        <?php } ?>
                                    </li>
                                    <li><span>My lists</span></li>
                                    <li> <a href="<?php print(isset($_SESSION["FullName"]) ? "shopping_list.php" : "javascript:void(0);"); ?>"> shopping list </a></li>
                                    <li><span>My Account</span></li>
                                    <li> <a href="<?php print(isset($_SESSION["FullName"]) ? "personal_data.php" : "javascript:void(0);"); ?>"> Personal Data <a href=""></a></li>
                                    <li> <a href="<?php print(isset($_SESSION["FullName"]) ? "my_order.php" : "javascript:void(0);"); ?>"> My Orders </a></li>
                                    <li> <a href="<?php print(isset($_SESSION["FullName"]) ? "my_address.php" : "javascript:void(0);"); ?>"> Addresses </a></li>
                                    <li>payment methods</li>
                                    <?php if (isset($_SESSION["FullName"])) { ?>
                                        <li><a href="logout.php">Logout</a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                    </li>
                    <li>
                        <div class="hdr_icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i> </div>
                        <div class="hdr_text side_cart_click"><a href="javascript:void(0)"><span id="header_quantity"> <?php print(isset($_SESSION['header_quantity']) ? $_SESSION['header_quantity'] : 0); ?> Items</span>Cart</a></div>
                        <div class="hdr_side_cart">
                            <div class="side_bar_close"><i class="fa fa-times" aria-hidden="true"></i></div>
                            <div class="side_cart_subtotal">
                                <div class="subtotal_title">Subtotal</div>
                                <div class="subtotal_prise" id="cart_amount">0,00 €</div>
                                <div class="full_width"><a id="cart_href" href="javascript:void(0)">
                                        <div class="gerenric_btn full_btn">Go to Basket</div>
                                    </a></div>
                            </div>
                            <div class="side_cart_pd" id="show_card_body">

                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <nav id="navigation_section" <?php print($background_color_one); ?>>
            <ul>
                <li class="all_menu"><a href="javascript:void(0)"><i class="fa fa-bars"></i> All</a></li>
                <?php
                $Query = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND parent_id = '0'";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                ?>
                        <li><a href="product_category.php?level_one=<?php print($row->group_id); ?>"> <?php print($row->cat_title); ?> </a></li>
                <?php
                    }
                }
                ?>
                <li><a href="product_category.php?level_one=20">Schulranzen</a></li>
                <li><a href="javascript:void(0);" class="nav_sale">Sales & Offers</a></li>
            </ul>
            <div class="nav_submenu">
                <div class="nav_submenu_logo"><a href="index.php"><img src="images/logo.png" alt=""></a>
                    <div class="submenu_close"><i class="fa fa-close"></i> </div>
                </div>
                <ul>
                    <?php
                    $Query = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND parent_id = '0'";
                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                    if (mysqli_num_rows($rs) > 0) {
                        while ($row = mysqli_fetch_object($rs)) {
                    ?>
                            <li><a href="product_category.php?level_one=<?php print($row->group_id); ?>"> <?php print($row->cat_title); ?> </a></li>
                    <?php
                        }
                    }
                    ?>
                    <li><a href="product_category.php?level_one=20">Schulranzen</a></li>
                </ul>
                <div class="nav_overlay"></div>
            </div>

        </nav>
        <?php if ($page == 0) {
            $Query1 = "SELECT sc.scat_id, sc.group_id, sc.scat_title_de AS scat_title, sc.scat_params_de AS scat_params FROM special_category AS sc WHERE sc.scat_status = '1' AND sc.group_id != '' ORDER BY sc.scat_orderby ASC";
            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
            if (mysqli_num_rows($rs1)) {
        ?>
                <div class="header_nav_2">
                    <ul>
                        <?php
                        while ($row1 = mysqli_fetch_object($rs1)) {
                        ?>
                            <li><a href="javascript:void(0)"><span><?php print($row1->scat_title); ?></span></a>
                                <div class="sub_menu">
                                    <?php
                                    $Query2 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.cat_status = '1' AND cat.group_id IN (" . $row1->group_id . ") ORDER BY cat.cat_id ASC";
                                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                                    if (mysqli_num_rows($rs2)) {
                                        //$row2 = mysqli_fetch_object($rs2);
                                        $row22 = array();
                                        while ($row2 = mysqli_fetch_object($rs2)) {
                                            $row22[] = $row2;
                                        }
                                        for ($i = 0; $i < count($row22); $i++) {
                                            //print(end($row2));die();
                                    ?>
                                            <div class="sub_menu_col">

                                                <h3><a href="products.php?level_two=<?php print($row22[$i]->group_id); ?>"><?php print($row22[$i]->cat_title); ?></a></h3>
                                                <ul>
                                                    <?php
                                                    $Query3 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.cat_status = '1' AND cat.parent_id = '" . $row22[$i]->group_id . "' ORDER BY  RAND() LIMIT 0,6";
                                                    $rs3 = mysqli_query($GLOBALS['conn'], $Query3);
                                                    if (mysqli_num_rows($rs3)) {
                                                        while ($row3 = mysqli_fetch_object($rs3)) {
                                                    ?>
                                                            <li><a href="products.php?level_three=<?php print($row3->group_id); ?>"><?php print($row3->cat_title); ?></a></li>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                                <?php $i++;
                                                if ($i < count($row22)) { ?>
                                                    <h3><a href="products.php?level_two=<?php print($row22[$i]->group_id); ?>"><?php print($row22[$i]->cat_title); ?></a></h3>
                                                    <ul>
                                                        <?php
                                                        $Query3 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.cat_status = '1' AND cat.parent_id = '" . $row22[$i]->group_id . "' ORDER BY  RAND() LIMIT 0,6";
                                                        $rs3 = mysqli_query($GLOBALS['conn'], $Query3);
                                                        if (mysqli_num_rows($rs3)) {
                                                            while ($row3 = mysqli_fetch_object($rs3)) {
                                                        ?>
                                                                <li><a href="products.php?level_three=<?php print($row3->group_id); ?>"><?php print($row3->cat_title); ?></a></li>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        <?php
                                        }
                                    }
                                    $Query4 = "SELECT gi.gimg_id, gi.gimg_title_de AS gimg_title, gi.scat_id, gi.gimg_file, gi.gimg_link FROM gallery_images AS gi WHERE gi.gimg_status = '1' AND gi.scat_id = '" . $row1->scat_id . "' ORDER BY gi.gimg_orderby ASC";
                                    $rs4 = mysqli_query($GLOBALS['conn'], $Query4);
                                    if (mysqli_num_rows($rs4)) {
                                        while ($row4 = mysqli_fetch_object($rs4)) {
                                        ?>
                                            <div class="sub_menu_col">
                                                <a class="sub_menu_div" href="<?php print(!empty($row4->gimg_link) ? $row4->gimg_link : 'javascript:void(0);'); ?>">
                                                    <div class="sub_menu_image"><img src="<?php print($GLOBALS['siteURL'] . "files/gallery_images/special_category/" . $row4->scat_id . "/" . $row4->gimg_file); ?>" alt=""></div>
                                                    <div class="sub_menu_title"><a href="<?php print(!empty($row4->gimg_link) ? $row4->gimg_link : 'javascript:void(0);'); ?>"><?php print($row4->gimg_title); ?></a></div>
                                                </a>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                        <!--<li><a href="javascript:void(0)"><span>LIGHTING</span></a></li>-->
                    </ul>
                </div>
        <?php  }
        } ?>
    </div>
</header>
<div class="header_overlay"></div>
<script>
    $(function() {
        //console.log("switch_click");
        //$(".switch_click").trigger("click");
        $(".switch_click").click(function() {
            //console.log("class switch_click");
            let utype_id = 3;
            let ci_total = 0;
            if ($(this).is(":checked")) {
                //console.log("if switch_click");
                utype_id = 4;
                $("#header_section .header_top").css('background-color', '<?php print(config_company_color_a); ?>');
                $("#header_section .header_bottom").css('background-color', '<?php print(config_company_color_b); ?>');
                $("#navigation_section").css('background-color', '<?php print(config_company_color_a); ?>');
                $("#footer_section").css('background-color', '<?php print(config_company_color_a); ?>');
                $(".pbp_price_with_tex").hide();
                $(".price_without_tex").show();
                ci_total = $("#ci_total").val();
            } else {
                //console.log("else switch_click");
                $("#header_section .header_top").css('background-color', '<?php print(config_private_color_a); ?>');
                $("#header_section .header_bottom").css('background-color', '<?php print(config_private_color_b); ?>');
                $("#navigation_section").css('background-color', '<?php print(config_private_color_a); ?>');
                $("#footer_section").css('background-color', '<?php print(config_private_color_a); ?>');
                $(".pbp_price_with_tex").show();
                $(".price_without_tex").hide();
                ci_total = $("#ci_total").val();
            }

            $.ajax({
                url: 'ajax_calls.php?action=switch_click',
                method: 'POST',
                data: {
                    utype_id: utype_id,
                    ci_total: ci_total
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    //console.log(obj.delivery_charges.total);
                    if (obj.status == 1) {
                        if (obj.delivery_charges.tex > 0) {
                            $("#cart_subtotal").show();
                            $("#cart_vat").show();
                        } else {
                            if (obj.delivery_charges.total == 0 && obj.utype_id == 4) {
                                $("#cart_subtotal").show();
                                $("#cart_vat").show();
                            } else {
                                $("#cart_subtotal").hide();
                                $("#cart_vat").hide();
                            }
                        }
                        let packing = (obj.delivery_charges.packing).toFixed(2)
                        let shipping = (obj.delivery_charges.shipping).toFixed(2)
                        let total = (obj.delivery_charges.total).toFixed(2)
                        $("#packing").text("Packaging fee (" + packing.replace(".", ",") + " €)");
                        $("#shipping").text("Shipping costs (" + shipping.replace(".", ",") + " €)");
                        $("#total").text(total.replace(".", ",") + " €");
                    } else {
                        if (obj.utype_id == 4) {
                            $("#cart_subtotal").show();
                            $("#cart_vat").show();
                        } else {
                            $("#cart_subtotal").hide();
                            $("#cart_vat").hide();
                        }
                    }

                }
            });
        });
    });
</script>