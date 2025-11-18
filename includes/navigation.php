<?php
$background_color_one = "";
$background_color_two = "";
$pbp_price_with_tex_display = "";
$price_without_tex_display = "";
$switch_click_check = "";
$switch_click_text = "Angebote gelten für Privatkunden. Preise inkl. gesetzl. MwSt.";
$display_check = 'style="display: none;"';
if (isset($_SESSION["utype_id"]) && $_SESSION['utype_id'] == 4) {
    $background_color_one = 'style="background-color: ' . config_company_color_a . ';"';
    $background_color_two = 'style="background-color: ' . config_company_color_b . ';"';
    $switch_click_check = "checked";
    $price_without_tex_display = 'style="display: block;"';
    $pbp_price_with_tex_display = 'style="display: none;"';
    $display_check = '';
    $switch_click_text = "Angebote gelten nur für Industrie, Handel, Handwerk und Gewerbe. Preise zzgl. gesetzl. MwSt.";
}
//print($_SESSION["FullName"]);
?>
<header id="header_section" class="header_sticky">
    <div class="header_top" <?php print($background_color_one); ?>>
        <ul>
            <li>
                <div class="gerenric_switch">
                    <span>Privatkunde</span>
                    <span>
                        <input class="switch_click" type="checkbox" hidden="hidden" id="username" <?php print($switch_click_check); ?>>
                        <label class="switch" for="username"></label>
                    </span>
                </div>
            </li>
            <li>Geschäftskunde</li>
            <li>&nbsp;</li>
            <li>&nbsp;</li>
            <li id="switch_click_text"><?php print($switch_click_text); ?></li>
        </ul>
        <ul>
            <li><a href="über-uns" title="Über uns">Über Wacker 24</a></li>
            <li><a href="kontakt" title="Kontaktformular">Kontakt</a></li>
            <li>
                <div class="header_language">
                    <div class="language_select"><a href="#" role="button" onclick="return false;" title="DE"><img src="images/gm_icon.webp" alt="DE"> Germany</a></div>
                    <!--<div class="language_select"><a href="javascript:void(0)"><img src="images/gm_icon.png" alt=""> Germany <i class="fa fa-caret-down" ></i></a></div>
                    <ul>
                        <li><a href="javascript:void(0)"><img src="images/en_icon.svg" alt=""> English</a></li>
                        <li><a href="javascript:void(0)"><img src="images/gm_icon.png" alt=""> Germany</a></li>
                    </ul>-->
                </div>
            </li>
        </ul>
    </div>
    <div class="header_sticky">
        <div class="header_bottom" <?php print($background_color_two); ?>>
            <div id="logo"><a href="<?php print($GLOBALS['siteURL']); ?>" title="logo" aria-label="logo"><img src="<?php print(config_site_logo) ?>" alt="logo"></a></div>
            <div class="header_location location_trigger"><i class="fa fa-map-marker" ></i> <span> <?php print((isset($_SESSION['ort']) && !empty($_SESSION['ort']))? $_SESSION['ort'] : 'Versand Germany'); ?> </span></div>
            <form class="header_search" name="frm_search" id="frm_search" method="GET" action="search_result.php" role="form" enctype="multipart/form-data">
                <div class="header_select">
                    <select class="header_select_slt" name="level_one" id="level_one" aria-label="Category">
                        <option value="0">Alle</option>
                        <?php FillSelected2("category", "group_id", "cat_title_de AS cat_title", $cat_id, "cat_status = '1' AND parent_id = '0'"); ?>
                    </select>
                </div>
                <input type="hidden" name="supplier_id" id="supplier_id" value="0">
                <input type="text" class="search_input search_keyword" name="search_keyword" id="search_keyword" value="<?php print($search_keyword); ?>" placeholder="Suchhbegriff" autocomplete="off">
                <button class="search_icon" title="search button"></button>
            </form>
            <style>
                
            </style>
            <a class="header_tint_toner_logo" href="tint-toner">
                <div class="tint_toner_logo"><img src="images/tint_toner_logo.png" alt=""></div>
                <div class="tint_toner_txt">Tinte / Toner</div>
            </a>
            <div class="header_account">
                <ul>
                    <li><a href="termine" title="termine">
                            <div class="appointment_booking"><i class="fa fa-check-square-o" ></i> Terminbuchung</div>
                        </a></li>
                    <li>
                        <div class="hdr_icon"><i class="fa fa-user" ></i> </div>
                        <div class="hdr_text">
                            <a href="#" role="button" onclick="return false;"><span> <?php print(isset($_SESSION["FullName"]) ? "Hi, " . $_SESSION["FullName"] : "Hello Login"); ?> <?php if( (isset($_SESSION["utype_id"]) && in_array($_SESSION["utype_id"], array(3,4))) || !isset($_SESSION["FullName"])){ ?> </span>Konto & Listen <i class="fa fa-caret-down"></i> <?php } ?> </a>
                            <?php if( (isset($_SESSION["utype_id"]) && in_array($_SESSION["utype_id"], array(3,4))) || !isset($_SESSION["FullName"])){ ?>
                            <div class="account_nav">
                                <ul>
                                    <li>
                                        <?php if (!isset($_SESSION["FullName"])) { ?>
                                            <div class="full_width txt_align_center mb-10"><a class="href_login" href="anmelden" title="anmelden">
                                                    <div class="gerenric_btn">Anmelden</div>
                                                </a></div>
                                            <div class="full_width txt_align_center">Neues Konto? <a href="registrierung"><b>Erstellen Sie hier.</b></a></div>
                                        <?php } ?>
                                    </li>
                                    <li><span>Meine Listen</span></li>
                                    <li> <a title="sonderpreise" href="<?php print(isset($_SESSION["FullName"]) ? "sonderpreise" : "#"); ?>"> Sonderpreise </a></li>
                                    <li> <a title="einkaufslisten" href="<?php print(isset($_SESSION["FullName"]) ? "einkaufslisten" : "#"); ?>"> Einkaufslisten </a></li>
                                    <li><span>Mein Konto</span></li>
                                    <li> <a title="benutzerprofile" href="<?php print(isset($_SESSION["FullName"]) ? "benutzerprofile" : "#"); ?>"> Persönliche Daten <a href=""></a></li>
                                    <li> <a title="bestellungen" href="<?php print(isset($_SESSION["FullName"]) ? "bestellungen" : "#"); ?>"> Bestellungen </a></li>
                                    <li> <a title="adressen" href="<?php print(isset($_SESSION["FullName"]) ? "adressen" : "#"); ?>"> Adressen </a></li>
                                    <li>Zahlungsarten</li>
                                    <?php if (isset($_SESSION["FullName"])) { ?>
                                        <li><a title="abmelden" href="abmelden">Abmelden</a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                    </li>
                    <li>
                        <div class="hdr_icon side_cart_click"><i class="fa fa-shopping-cart" ></i> </div>
                        <!--<div class="hdr_text side_cart_click"><a href="javascript:void(0)"><span id="header_quantity"> <?php print(isset($_SESSION['header_quantity']) ? $_SESSION['header_quantity'] : 0); ?> Artikel</span>Einkaufswagen</a></div>-->
                        <div class="hdr_text"><a title="einkaufswagen" href="<?php print(isset($_SESSION['header_quantity']) ? 'einkaufswagen' : '#'); ?>" id="cart_click_href"><span id="header_quantity"> <?php print(isset($_SESSION['header_quantity']) ? $_SESSION['header_quantity'] : 0); ?> Artikel</span>Einkaufswagen</a></div>
                        <div class="hdr_side_cart">
                            <div class="side_bar_close"><i class="fa fa-times" ></i></div>
                            <div class="side_cart_subtotal">
                                <div class="subtotal_title">Zwischensumme</div>
                                <div class="subtotal_prise" id="cart_amount">0,00 €</div>
                                <div class="full_width"><a title="Einkaufswagen" id="cart_href" href="#">
                                        <div class="gerenric_btn full_btn">Einkaufswagen</div>
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
                <li class="all_menu"><a title="Alle" href="#" role="button" onclick="return false;"><i class="fa fa-bars"></i> Alle</a></li>
                <?php
                $Query = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title, cat_params_de AS cat_params FROM category WHERE cat_status = '1' AND parent_id = '0'";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                ?>
                        <li><a title="<?php print($row->cat_title); ?>" href="unterkategorien/<?php print($row->cat_params); ?>"> <?php print($row->cat_title); ?> </a></li>
                <?php
                    }
                }
                ?>
                <li><a href="kostenlose-produktgeschenke" class="green"><b>Gratis</b></a></li>
                <li><a title="verkäufe-angebote" href="verkaeufe-angebote" class="nav_sale">Verkäufe & Angebote</a></li>
            </ul>
            <div class="nav_submenu">
                <div class="nav_submenu_logo"><a title="logo" href="index.php"><img src="images/logo.png" alt="logo"></a>
                    <div class="submenu_close"><i class="fa fa-close"></i> </div>
                </div>
                <ul>
                    <?php
                    $Query = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title, cat_params_de AS cat_params FROM category WHERE cat_status = '1' AND parent_id = '0'";
                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                    if (mysqli_num_rows($rs) > 0) {
                        while ($row = mysqli_fetch_object($rs)) {
                    ?>
                            <li><a title="<?php print($row->cat_title); ?>" href="unterkategorien/<?php print($row->cat_params); ?>"> <?php print($row->cat_title); ?> </a></li>
                    <?php
                        }
                    }
                    ?>
                    <li><a href="kostenlose-produktgeschenke" class="green"><b>Gratis</b></a></li>
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
                            <li><a title="<?php print($row1->scat_title); ?>" href="#" role="button" onclick="return false;"><span><?php print($row1->scat_title); ?></span></a>
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

                                                <h3><a title="<?php print($row22[$i]->cat_title); ?>" href="artikelarten/<?php print($row22[$i]->cat_params); ?>"><?php print($row22[$i]->cat_title); ?></a></h3>
                                                <ul>
                                                    <?php
                                                    $Query3 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.cat_status = '1' AND cat.parent_id = '" . $row22[$i]->group_id . "' ORDER BY  RAND() LIMIT 0,6";
                                                    $rs3 = mysqli_query($GLOBALS['conn'], $Query3);
                                                    if (mysqli_num_rows($rs3)) {
                                                        while ($row3 = mysqli_fetch_object($rs3)) {
                                                    ?>
                                                            <li><a title="<?php print($row3->cat_title); ?>" href="artikelarten/<?php print($row22[$i]->cat_params."/".$row3->cat_params); ?>"><?php print($row3->cat_title); ?></a></li>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                                <?php $i++;
                                                if ($i < count($row22)) { ?>
                                                    <h3><a title="<?php print($row22[$i]->cat_title); ?>" href="artikelarten/<?php print($row22[$i]->cat_params); ?>"><?php print($row22[$i]->cat_title); ?></a></h3>
                                                    <ul>
                                                        <?php
                                                        $Query3 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.cat_status = '1' AND cat.parent_id = '" . $row22[$i]->group_id . "' ORDER BY  RAND() LIMIT 0,6";
                                                        $rs3 = mysqli_query($GLOBALS['conn'], $Query3);
                                                        if (mysqli_num_rows($rs3)) {
                                                            while ($row3 = mysqli_fetch_object($rs3)) {
                                                        ?>
                                                                <li><a title="<?php print($row3->cat_title); ?>" href="artikelarten/<?php print($row22[$i]->cat_params."/".$row3->cat_params); ?>"><?php print($row3->cat_title); ?></a></li>
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
                                                <a class="sub_menu_div" title="<?php print($row4->gimg_title); ?>" href="<?php print(!empty($row4->gimg_link) ? $GLOBALS['siteURL'].$row4->gimg_link : '#'); ?>">
                                                    <div class="sub_menu_image"><img src="<?php print($GLOBALS['siteURL'] . "files/gallery_images/special_category/" . $row4->scat_id . "/" . $row4->gimg_file); ?>" alt="<?php print($row4->gimg_title); ?>"></div>
                                                    <div class="sub_menu_title"><a title="<?php print($row4->gimg_title); ?>" href="<?php print(!empty($row4->gimg_link) ? $GLOBALS['siteURL'].$row4->gimg_link : '#'); ?>"><?php print($row4->gimg_title); ?></a></div>
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
