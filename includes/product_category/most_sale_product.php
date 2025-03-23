<?php $Query = "SELECT DISTINCT oi.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id WHERE oi.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) AND cm.cm_type = '" . $pro_type . "') AND pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> ''";
//print($Query);die();
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
?>
    <div class="gerenric_white_box">
        <div class="gerenric_product full_column">
            <h2>Meist verkaufte Produkte</h2>
            <div class="gerenric_slider">
                <?php
                while ($row = mysqli_fetch_object($rs)) {
                ?>
                    <div>
                        <div class="pd_card">
                            <div class="pd_image"><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"><img loading="lazy" src="<?php print(get_image_link(160, $row->pg_mime_source_url)); ?>" alt=""></a></div>
                            <div class="pd_detail">
                                <h5><a href="product/<?php print($row->supplier_id); ?>/<?php print(url_clean($row->pro_description_short)); ?>"> <?php print($row->pro_description_short); ?> </a></h5>
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
                                    <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                    <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>> <?php print("<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>"); ?> </div>
                                <?php } else { ?>
                                    <div class="pd_prise price_without_tex" <?php print($price_without_tex_display); ?>><?php print(price_format($row->pbp_price_without_tax)); ?>€</div>
                                    <div class="pd_prise pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>><?php print(price_format($row->pbp_price_amount)); ?>€</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php } ?>