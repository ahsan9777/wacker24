<footer id="footer_section" <?php print($background_color_one); ?>>
    <div class="page_width">
        <div class="footer_top">
            <?php
            $Query1 = "SELECT footer_id, footer_title_de AS footer_title FROM footer WHERE footer_status = '1' ORDER BY footer_orderby ASC";
            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
            if (mysqli_num_rows($rs1) > 0) {
                while ($row1 = mysqli_fetch_object($rs1)) {
            ?>
                    <div class="footer-col">
                        <h2><?php print($row1->footer_title); ?></h2>
                        <ul>
                            <?php
                            $Query2 = "SELECT cnt_id, cnt_slug, cnt_title_de AS cnt_title FROM contents WHERE cnt_status = '1' AND footer_id = '".$row1->footer_id."' ORDER BY cnt_orderby ASC";
                            $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                            if (mysqli_num_rows($rs2) > 0) {
                                while ($row2 = mysqli_fetch_object($rs2)) {
                            ?>
                                    <li><a href="<?php print($row2->cnt_slug); ?>"><?php print($row2->cnt_title); ?></a></li>
                            <?php

                                }
                            }
                            ?>
                        </ul>
                    </div>
            <?php
                }
            }
            ?>
            <div class="footer-col">
                <h2>Kontakt & Anfahrt</h2>
                <ul>
                    <li><a href="kontakt">Kontaktformular</a></li>
                    <li><a href="kontakt">Öffnungszeiten</a></li>
                    <li><a href="kontakt">Anfahrt</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h2>Zahlungsarten</h2>
                <div class="footer_payment_method">
                    <?php
                    $Query = "SELECT pm.pm_id, pm_title_de AS pm_title, pm.pm_image FROM payment_method AS pm WHERE pm.pm_status = '1' ORDER BY pm.pm_orderby ASC";
                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                    if (mysqli_num_rows($rs) > 0) {
                        while ($row = mysqli_fetch_object($rs)) {
                            $pm_image_href = "files/no_img_1.jpg";
                            if (!empty($row->pm_image)) {
                                $pm_image_href = $GLOBALS['siteURL'] . "files/payment_method/" . $row->pm_image;
                            }
                    ?>
                            <div class="payment_card">
                                <div class="payment_card_image"><img src="<?php print($pm_image_href); ?>" alt="<?php print($row->pm_title) ?>" title="<?php print($row->pm_title) ?>"></div>
                                <div class="payment_card_title"><?php print($row->pm_title) ?></div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="footer_bottom">
            <div class="footer_btm_left">
                <div class="social_medial">
                    <ul>
                        <li><a href="#" aria-label="Youtube"><img src="images/youtube_icon.webp" alt=""></a></li>
                        <li><a href="#" aria-label="Facebook"><img src="images/facebook_icon.webp" alt=""></a></li>
                        <li><a href="#" aria-label="Twitter" ><img src="images/twitter_icon.webp" alt=""></a></li>
                        <li><a href="#" aria-label="Instagram" ><img src="images/instagram_icon.webp" alt=""></a></li>
                    </ul>
                </div>
            </div>
            <div class="footer_btm_right">
                <button class="subscribe_newsletter_btn">Newsletter abonnieren</button>
            </div>

        </div>
        <div class="footer_logo"><a href="<?php print($GLOBALS['siteURL']); ?>" aria-label="site logo"><img src="<?php print(config_site_logo) ?>" alt=""></a></div>
    </div>
</footer>