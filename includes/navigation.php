<?php
$background_color_one = "";
$background_color_two = "";
$pbp_price_with_tex_display = "";
$price_without_tex_display = "";
$switch_click_check = "";
if(isset($_SESSION['utype_id']) && $_SESSION['utype_id'] == 4){
    $background_color_one = 'style="background-color: rgb(72, 132, 252);"';
    $background_color_two = 'style="background-color: rgb(1, 31, 67);"';
    $switch_click_check = "checked";
    $price_without_tex_display = 'style="display: block;"';
    $pbp_price_with_tex_display = 'style="display: none;"';
}
?>
<header id="header_section" class="header_sticky">
    <div class="header_top" <?php print($background_color_one); ?> >
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
            <li><a href="contact_page.html">Contact</a></li>
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
    <div>
        <div class="header_bottom" <?php print($background_color_two); ?>>
            <div id="logo"><a href="index.php"><img src="images/logo.png" alt=""></a></div>
            <div class="header_location location_trigger"><i class="fa fa-map-marker" aria-hidden="true"></i> Versand <span>Germany</span></div>
            <form class="header_search" name="frm" id="frm" method="post" action="search_result.php" role="form" enctype="multipart/form-data">
                <div class="header_select">
                    <select class="header_select_slt" name="cat_id" id="cat_id">
                        <option value="0">ALL</option>
                        <?php FillSelected2("category", "group_id", "cat_title_de AS cat_title", $cat_id, "cat_status = '1' AND parent_id = '0'"); ?>
                    </select>
                </div>
                <input type="text" class="search_input" name="search_keyword" id="search_keyword" value="<?php print($search_keyword); ?>" required placeholder="Suchhbegriff">
                <button class="search_icon"></button>
            </form>
            <div class="header_account">
                <ul>
                    <li><a href="javascript:void(0)">
                            <div class="appointment_booking"><i class="fa fa-check-square-o" aria-hidden="true"></i> appointment booking</div>
                        </a></li>
                    <li>
                        <div class="hdr_icon"><i class="fa fa-user" aria-hidden="true"></i> </div>
                        <div class="hdr_text">
                            <a href="javascript:void(0)"><span>Hello Login</span>Account & List <i class="fa fa-caret-down"></i></a>
                            <div class="account_nav">
                                <ul>
                                    <li>
                                        <div class="full_width txt_align_center mb-10"><a href="login_page.html">
                                                <div class="gerenric_btn">Login</div>
                                            </a></div>
                                        <div class="full_width txt_align_center">New Account? <a href="register_page.html"><b>Create One here</b></a></div>
                                    </li>
                                    <li><span>My lists</span></li>
                                    <li>shopping list</li>
                                    <li><span>My Account</span></li>
                                    <li>Personal Data</li>
                                    <li>orders</li>
                                    <li>addresses</li>
                                    <li>payment methods</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="hdr_icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i> </div>
                        <div class="hdr_text side_cart_click"><a href="javascript:void(0)"><span>0 Items</span>Cart</a></div>
                        <div class="hdr_side_cart">
                            <div class="side_bar_close"><i class="fa fa-times" aria-hidden="true"></i></div>
                            <div class="side_cart_subtotal">
                                <div class="subtotal_title">Subtotal</div>
                                <div class="subtotal_prise">€4.90</div>
                                <div class="full_width"><a href="product_cart.html">
                                        <div class="gerenric_btn full_btn">Go to Basket</div>
                                    </a></div>
                            </div>
                            <div class="side_cart_pd">
                                <div class="side_cart_pd_row">
                                    <div class="side_cart_pd_image"><a href="javascript:void(0)"><img
                                                src="images/product_img1.jpg" alt=""></a></div>
                                    <div class="side_cart_pd_prise">€4.90</div>
                                    <div class="side_cart_pd_qty">
                                        <div class="side_pd_qty">
                                            <input type="number" class="qlt_number" value="1">
                                        </div>
                                        <div class="side_pd_delete"><a href="javascript:void(0)"><i
                                                    class="fa fa-trash"></i></a></div>
                                    </div>
                                </div>
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
                <li><a href="javascript:void(0);">Satchel</a></li>
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
                    <li><a href="javascript:void(0);">Satchel</a></li>
                </ul>
                <div class="nav_overlay"></div>
            </div>

        </nav>
        <?php if ($page == 0) { ?>
            <div class="header_nav_2">
                <ul>
                    <li><a href="javascript:void(0)"><span>HOME & KITCHEN</span></a>
                        <div class="sub_menu">
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <div class="sub_menu_div">
                                    <div class="sub_menu_image"><img src="images/product_img1.jpg" alt=""></div>
                                    <div class="sub_menu_title"><a href="javascript:void(0)">BEDROOOM</a></div>
                                </div>
                            </div>
                            <div class="sub_menu_col">
                                <div class="sub_menu_div">
                                    <div class="sub_menu_image"><img src="images/product_img2.jpg" alt=""></div>
                                    <div class="sub_menu_title"><a href="javascript:void(0)">BEDROOOM</a></div>
                                </div>
                            </div>
                            <div class="sub_menu_col">
                                <div class="sub_menu_div">
                                    <div class="sub_menu_image"><img src="images/pd_img1.jfif" alt=""></div>
                                    <div class="sub_menu_title"><a href="javascript:void(0)">BEDROOOM</a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="javascript:void(0)"><span>SPECIAL OFFERS</span></a></li>
                    <li><a href="javascript:void(0)"><span>FURNITURE</span></a>
                        <div class="sub_menu">
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <div class="sub_menu_div">
                                    <div class="sub_menu_image"><img src="images/product_img2.jpg" alt=""></div>
                                    <div class="sub_menu_title"><a href="javascript:void(0)">BEDROOOM</a></div>
                                </div>
                            </div>
                            <div class="sub_menu_col">
                                <div class="sub_menu_div">
                                    <div class="sub_menu_image"><img src="images/pd_img1.jfif" alt=""></div>
                                    <div class="sub_menu_title"><a href="javascript:void(0)">BEDROOOM</a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="javascript:void(0)"><span>LARGE APPLIANCES</span></a>
                        <div class="sub_menu">
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <div class="sub_menu_div">
                                    <div class="sub_menu_image"><img src="images/pd_img1.jfif" alt=""></div>
                                    <div class="sub_menu_title"><a href="javascript:void(0)">BEDROOOM</a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="javascript:void(0)"><span>SMALL APPLIANCES</span></a>
                        <div class="sub_menu">
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                            <div class="sub_menu_col">
                                <h3><a href="javascript:void(0)">LIVING ROOM</a></h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                                <h3>LIVING ROOM</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Chairs</a></li>
                                    <li><a href="javascript:void(0)">Sofas & Couches</a></li>
                                    <li><a href="javascript:void(0)">Tables</a></li>
                                    <li><a href="javascript:void(0)">Cabinets</a></li>
                                    <li><a href="javascript:void(0)">Bookcases</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li><a href="javascript:void(0)"><span>COOKING & DINING</span></a></li>
                    <li><a href="javascript:void(0)"><span>HOME TEXTILES</span></a></li>
                    <li><a href="javascript:void(0)"><span>LIGHTING</span></a></li>
                </ul>
            </div>
        <?php } ?>
    </div>
</header>