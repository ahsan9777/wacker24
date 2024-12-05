<header id="header_section" class="header_sticky">
    <div class="header_top">
        <ul>
            <li>
                <div class="gerenric_switch">
                    <span>Private Customer</span>
                    <span>
                        <input class="switch_click" type="checkbox" hidden="hidden" id="username">
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
    <div >
        <div class="header_bottom">
            <div id="logo"><a href="index.php"><img src="images/logo.png" alt=""></a></div>
            <div class="header_location location_trigger"><i class="fa fa-map-marker" aria-hidden="true"></i> Versand <span>Germany</span></div>
            <div class="header_search">
                <div class="header_select">
                    <select class="header_select_slt">
                        <option value="">ALL</option>
                        <option value="">Organize & Register</option>
                        <option value="">Papers & Pads</option>
                        <option value="">Write</option>
                        <option value="">Gluing & Shipping</option>
                        <option value="">Presentation & Planning</option>
                        <option value="">Technology & Accessories</option>
                        <option value="">ink & toner</option>
                        <option value="">Useful things in the office</option>
                    </select>
                </div>
                <input type="text" class="search_input" placeholder="Suchhbegriff">
                <button class="search_icon"></button>
            </div>
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
        <nav id="navigation_section">
            <ul>
                <li class="all_menu"><a href="javascript:void(0)"><i class="fa fa-bars"></i> All</a></li>
                <?php 
                $Query = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND parent_id = '0'";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if(mysqli_num_rows($rs) > 0){
                    while($row = mysqli_fetch_object($rs)){
                ?>
                <li><a href="product_category.php?level_one=<?php print($row->group_id); ?>"> <?php print($row->cat_title); ?> </a></li>
                <?php 
                    }
                }
                ?>
                <li><a href="product_category.html">Satchel</a></li>
                <li><a href="product_category.html" class="nav_sale">Sales & Offers</a></li>
            </ul>
            <div class="nav_submenu">
                <div class="nav_submenu_logo"><a href="index.php"><img src="images/logo.png" alt=""></a>
                    <div class="submenu_close"><i class="fa fa-close"></i> </div>
                </div>
                <ul>
                    <li><a href="product_category.html">Organize & Register</a></li>
                    <li><a href="product_category.html">Papers & Pads</a></li>
                    <li><a href="product_category.html">Write</a></li>
                    <li><a href="product_category.html">Gluing & Shipping</a></li>
                    <li><a href="product_category.html">Presentation & Planning</a></li>
                    <li><a href="product_category.html">Technology & Accessories</a></li>
                    <li><a href="product_category.html">Ink & toner</a></li>
                    <li><a href="product_category.html">Useful things in the office</a></li>
                    <li><a href="product_category.html">Satchel</a></li>
                </ul>
                <div class="nav_overlay"></div>
            </div>

        </nav>
        <?php if($page == 0){ ?>
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