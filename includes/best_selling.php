<div class="hm_section_2">
    <div class="gerenric_white_box">
        <div class="gerenric_product full_column">
            <h1 class="pd_heading">Meist verkaufte Produkte</h1>
            <div class="gerenric_slider">
                <?php
                $special_price = "";
                $Query = "SELECT DISTINCT oi.supplier_id, oi.ord_id, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pbp.pbp_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id WHERE pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> '' GROUP BY oi.supplier_id";
                //print($Query);//die();
                $rs = mysqli_query($conn, $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($rw = mysqli_fetch_object($rs)) {
                        $TotalRecords = TotalRecords("ord_id", "order_items", "WHERE ord_id = '".$rw->ord_id."'");
                ?>
                        <div>
                            <div class="pd_card">
                                <div class="pd_image">
                                    <a  tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>">
                                        <img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>">
                                        <?php
                                        if($TotalRecords > 80){
                                            print('<span class="pd_tag">Best Seller</span>');
                                        }
                                        ?>
                                    </a>
                                </div>
                                <div class="pd_detail">
                                    <h5><a  tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
            <!--<div class="gerenric_show_All"><a href="javascript:void(0)">Mehr anzeigen</a></div>-->
        </div>
    </div>
    <div class="gerenric_white_box">
        <div class="gerenric_product full_column">
            <h2>Schulranzen</h2>
            <div class="gerenric_slider">
                <?php
                $special_price = "";
                $Query = "SELECT * FROM vu_category_map AS cm WHERE cm.pro_status = '1' AND cm.cat_id_level_one = '20' AND cm.cm_type = '20' ORDER BY  RAND() LIMIT 0,12";
                //print($Query2);die();
                $rs = mysqli_query($conn, $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($rw = mysqli_fetch_object($rs)) {
                ?>
                        <div>
                            <div class="pd_card">
                                <div class="pd_image"><a  tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $rw->pg_mime_source_url)); ?>" alt="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"></a></div>
                                <div class="pd_detail">
                                    <h5><a  tabindex="-1" href="<?php print(product_detail_url($rw->supplier_id)); ?>" title="<?php print($rw->pro_udx_seo_internetbezeichung); ?>"> <?php print($rw->pro_description_short); ?> </a></h5>
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
            <div class="gerenric_show_All"><a  tabindex="-1" href="unterkategorien/schulranzen" title="Schulranzen">Mehr anzeigen</a></div>
        </div>
    </div>
    <?php
    $Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title FROM category WHERE cat_status = '1' AND cat_showhome_feature = '1'";
    $rs1 = mysqli_query($conn, $Query1);
    if (mysqli_num_rows($rs1) > 0) {
        while ($row1 = mysqli_fetch_object($rs1)) {
            $special_price = user_special_price("level_one", $row1->group_id);
            
    ?>
            <div class="gerenric_white_box">
                <div class="gerenric_product full_column">
                    <h2>Beliebte Produkte in <?php print($row1->cat_title); ?></h2>
                    <div class="gerenric_slider">
                        <?php
                        //$Query2 = "SELECT cm.cat_id, cm.sub_group_ids, cm.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE FIND_IN_SET(".$row1->group_id.", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
                        $Query2 = "SELECT * FROM vu_category_map AS cm  WHERE cm.cat_id_level_one = '" . $row1->group_id . "' ORDER BY  RAND() LIMIT 0,12";
                        //print($Query2);die();
                        $rs2 = mysqli_query($conn, $Query2);
                        if (mysqli_num_rows($rs2) > 0) {
                            while ($row2 = mysqli_fetch_object($rs2)) {
                        ?>
                                <div>
                                    <div class="pd_card">
                                        <div class="pd_image"><a  tabindex="-1" href="<?php print(product_detail_url($row2->supplier_id)); ?>" title="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row2->pg_mime_source_url)); ?>" alt="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"></a></div>
                                        <div class="pd_detail">
                                            <h5><a  tabindex="-1" href="<?php print(product_detail_url($row2->supplier_id)); ?>" title="<?php print($row2->pro_udx_seo_internetbezeichung); ?>"> <?php print($row2->pro_description_short); ?> </a></h5>
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
                                                <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax), $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                                <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount) ) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], ((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount), $special_price['usp_discounted_value'], $row2->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                            <?php } else { ?>
                                                <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_without_tax > 0) ? $row2->pbp_special_price_without_tax : $row2->pbp_price_without_tax) )); ?>€</div>
                                                <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format( ((config_site_special_price > 0 && $row2->pbp_special_price_amount > 0) ? $row2->pbp_special_price_amount : $row2->pbp_price_amount) )); ?>€</div>
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
                <div class="gerenric_show_All"><a  tabindex="-1" href="unterkategorien/<?php print(returnName("cat_params_de AS cat_params","category","group_id",$row1->group_id)); ?>" title="<?php print($row1->cat_title); ?>">Mehr anzeigen</a></div>
            </div>
    <?php
        }
    }
    ?>


</div>