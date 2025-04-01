<div class="hm_section_3 margin_top_30">
    <div class="gerenric_white_box">
        <div class="gerenric_product full_column mostviewed padding_left_right_10">
            <h2>Baumarkt</h2>
            <div class="gerenric_slider_mostviewed">
                <?php
                $special_price = "";
                $Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '91700' AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
                //print($Query);die();
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($rw = mysqli_fetch_object($rs)) {
                ?>
                        <div>
                            <div class="pd_card txt_align_left">
                                <div class="pd_image"><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
                                    <div class="pd_rating">
                                        <ul>
                                            <li>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php if (!empty($special_price)) { ?>
                                        <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                        <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($rw->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                    <?php } else { ?>
                                        <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($rw->pbp_price_without_tax)); ?>€</div>
                                        <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($rw->pbp_price_amount)); ?>€</div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="hm_section_3">
    <div class="gerenric_white_box">
        <div class="gerenric_product full_column mostviewed padding_left_right_10">
            <div class="gerenric_slider_mostviewed">
                <?php
                $special_price = "";
                $Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '91700' AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
                //print($Query);die();
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($rw = mysqli_fetch_object($rs)) {
                ?>
                        <div>
                            <div class="pd_card txt_align_left">
                                <div class="pd_image"><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a href="product/<?php print($rw->supplier_id); ?>/<?php print(url_clean($rw->pro_description_short)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
                                    <div class="pd_rating">
                                        <ul>
                                            <li>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                                <div class="fa fa-star"></div>
                                            </li>
                                        </ul>
                                    </div>
                                    <?php if (!empty($special_price)) { ?>
                                        <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_without_tax . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_without_tax, $special_price['usp_discounted_value']) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                        <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . $rw->pbp_price_amount . "€</del> <span class='pd_prise_discount'>" . discounted_price($special_price['usp_price_type'], $rw->pbp_price_amount, $special_price['usp_discounted_value'], 1) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                    <?php } else { ?>
                                        <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_without_tax)); ?>€</div>
                                        <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(str_replace(".", ",", $rw->pbp_price_amount)); ?>€</div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="gerenric_white_box">
        <div class="hm_register">
            <div class="full_width txt_align_center"><a href="anmelden">
                    <div class="gerenric_btn">Anmelden</div>
                </a></div>
            <p>Neues Konto? <a href="registrierung">Erstellen Sie hier.</a></p>
        </div>
    </div>
</div>