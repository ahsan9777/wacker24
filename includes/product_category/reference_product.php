<h2>Referenzen der Produkte</h2>
<div class="gerenric_slider">
    <?php
    $Query = "SELECT * FROM vu_category_map AS cm  WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
    //print($Query);die();
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
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
    }
    ?>
</div>