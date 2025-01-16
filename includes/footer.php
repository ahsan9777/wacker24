<footer id="footer_section" <?php print($background_color_one); ?>>
    <div class="page_width">
        <div class="footer_top">
            <div class="footer-col">
                <h2>About Wacker</h2>
                <ul>
                    <li><a href="javascript:void(0)">About Us</a></li>
                    <li><a href="javascript:void(0)">Data protection</a></li>
                    <li><a href="javascript:void(0)">Terms and Conditions</a></li>
                    <li><a href="javascript:void(0)">Partner</a></li>
                    <li><a href="javascript:void(0)">Wacker and the Environment</a></li>
                    <li><a href="javascript:void(0)">Imprint</a></li>
                    <li><a href="javascript:void(0)">Cancellation policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h2>Service</h2>
                <ul>
                    <li><a href="javascript:void(0)">Disposal of old devices</a></li>
                    <li><a href="javascript:void(0)">Return of used batteries</a></li>
                    <li><a href="javascript:void(0)">Return of packaging material</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h2>Discover More</h2>
                <ul>
                    <li><a href="javascript:void(0)">Copy Shop</a></li>
                    <li><a href="javascript:void(0)">Test</a></li>
                    <li><a href="javascript:void(0)">DHL parcel shop</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h2>Contact & Directions</h2>
                <ul>
                    <li><a href="javascript:void(0)">Contact form</a></li>
                    <li><a href="javascript:void(0)">Opening hours</a></li>
                    <li><a href="javascript:void(0)">Directions</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h2>Payment Methods</h2>
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
                                <div class="payment_card_image"><img src="<?php print($pm_image_href); ?>" alt="<?php print($row->pm_title) ?>" title="<?php print($row->pm_title) ?>" ></div>
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
                        <li><a href="javascript:void(0)"><img src="images/youtube_icon.png" alt=""></a></li>
                        <li><a href="javascript:void(0)"><img src="images/facebook_icon.png" alt=""></a></li>
                        <li><a href="javascript:void(0)"><img src="images/twitter_icon.png" alt=""></a></li>
                        <li><a href="javascript:void(0)"><img src="images/instagram_icon.png" alt=""></a></li>
                    </ul>
                </div>
            </div>
            <div class="footer_btm_right">
                <button class="subscribe_newsletter_btn">Subscribe to Newsletter</button>
            </div>

        </div>
        <div class="footer_logo"><a href="index.html"><img src="images/logo.png" alt=""></a></div>
    </div>
</footer>