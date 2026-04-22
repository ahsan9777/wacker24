<div class="hm_section_3">
    <div class="gerenric_white_box">
        <div class="gerenric_product full_column mostviewed padding_left_right_10">
            <div class="gerenric_slider_mostviewed">
                <?php
                $special_price = "";
                $Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id = '91700' AND cm.cm_type = '0' ORDER BY  RAND() LIMIT 0,12";
                //print($Query);die();
                $rs = mysqli_query($conn, $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($rw = mysqli_fetch_object($rs)) {
                ?>
                        <div>
                            <div class="pd_card txt_align_left">
                                <div class="pd_image"><a  tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a  tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
                                        <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) , $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                        <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) , $special_price['usp_discounted_value'], $rw->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                    <?php } else { ?>
                                        <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax) )); ?>€</div>
                                        <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $rw->pbp_special_price_amount > 0) ? $rw->pbp_special_price_amount : $rw->pbp_price_amount) )); ?>€</div>
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
            <div class="full_width txt_align_center"><a  tabindex="-1" href="anmelden" title="Anmelden">
                    <div class="gerenric_btn">Anmelden</div>
                </a></div>
            <p>Neues Konto? <a  tabindex="-1"  href="registrierung" title="Erstellen Sie hier">Erstellen Sie hier.</a></p>
        </div>
    </div>
    </div>