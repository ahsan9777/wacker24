<div class="hm_section_1">
    <div class="gerenric_product_category">
        <?php
        //$Query1 = "SELECT * FROM user_special_price WHERE user_id = 0 AND usp_status = '1'  ORDER BY CASE WHEN supplier_id IS NOT NULL THEN 1 WHEN level_two_id IS NOT NULL AND supplier_id = 0 THEN 2 ELSE 3 END, RAND() LIMIT 1";
        $Query1 = "SELECT usp.*, pro.pro_status FROM user_special_price AS usp LEFT OUTER JOIN products AS pro ON pro.supplier_id = usp.supplier_id WHERE usp.user_id = 0 AND usp.usp_status = '1'   ORDER BY CASE WHEN usp.supplier_id IS NOT NULL AND pro.pro_status = '1' THEN 1 WHEN usp.level_two_id IS NOT NULL AND usp.supplier_id = 0 THEN 2 ELSE 3 END, RAND() LIMIT 1";
        //print($Query1);
        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
        if (mysqli_num_rows($rs1) > 0) {
            $row1 = mysqli_fetch_object($rs1);
        ?>
                <div class="pd_ctg_block pd_ctg_special_sale">
                    <div class="pd_ctg_heading">SALE <i class="fa fa-tag" aria-hidden="true"></i></div>
                    <div class="pd_ctg_row">
                        <?php
                        $whereclause = "WHERE 1=1";
                        if($row1->supplier_id > 0){
                            $retArray = retArray("SELECT supplier_id FROM user_special_price WHERE user_id = 0 AND usp_status = '1' AND supplier_id > 0");
                            //print_r($retArray);
                            $supplier_id_data = "";
                            for($i = 0; $i < count($retArray); $i++){
                                $supplier_id_data .= "'".$retArray[$i]."',";
                            }
                            //$special_price = user_special_price("supplier_id", $row1->supplier_id);
                            $whereclause .= " AND supplier_id IN (".rtrim($supplier_id_data, ',').")";
                        } elseif($row1->level_two_id > 0){
                            $special_price = user_special_price("level_two", $row1->level_two_id, 0, 1);
                            $whereclause .= " AND FIND_IN_SET(".$row1->level_two_id.", pro.sub_group_ids)";
                        } elseif($row1->level_one_id > 0){
                            $special_price = user_special_price("level_one", $row1->level_one_id, 0, 1);
                            $whereclause .= " AND FIND_IN_SET(".$row1->level_one_id.", pro.sub_group_ids)";
                        }
                        $Query2 = "SELECT * FROM vu_products AS pro ".$whereclause."  ORDER BY  RAND() LIMIT 0,4";
                        //print($Query2);
                        $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                        if (mysqli_num_rows($rs2) > 0) {
                            while ($row2 = mysqli_fetch_object($rs2)) {
                                if($row1->supplier_id > 0){
                                    //$special_price = array();
                                    $special_price = user_special_price("supplier_id", $row2->supplier_id, 0, 1);
                                    //print_r($special_price);
                                }
                        ?>
                                <div class="pd_ctg_card">
                                    <a href="product/<?php print($row2->supplier_id); ?>/<?php print(url_clean($row2->pro_description_short)); ?>">
                                        <div class="pd_ctg_image">
                                            <img loading="lazy" src="<?php print(get_image_link(160, $row2->pg_mime_source_url)); ?>" alt="">
                                            <span class="pd_tag"><?php print($special_price['usp_discounted_value'].(($special_price['usp_price_type'] > 0) ? '€' : '%')); ?> OFF</span>
                                        </div>
                                        <div class="pd_ctg_title price_without_tex" <?php print($price_without_tex_display); ?>>
                                            <del><?php print(price_format($row2->pbp_price_without_tax)); ?>€</del> | <span class="pd_ctg_discount_price"><?php print(price_format(discounted_price($special_price['usp_price_type'], $row2->pbp_price_without_tax, $special_price['usp_discounted_value']))); ?>€ </span>
                                        </div>
                                        <div class="pd_ctg_title pbp_price_with_tex" <?php print($pbp_price_with_tex_display); ?>>
                                            <del><?php print(price_format($row2->pbp_price_amount)); ?>€</del> | <span class="pd_ctg_discount_price"><?php print(price_format(discounted_price($special_price['usp_price_type'], $row2->pbp_price_amount, $special_price['usp_discounted_value'], $row2->pbp_tax))); ?>€</span>
                                        </div>
                                    </a>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
        $Query1 = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title, cat_params_de AS cat_params FROM category WHERE cat_status = '1' AND cat_showhome = '1'";
        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
        if (mysqli_num_rows($rs1) > 0) {
            while ($row1 = mysqli_fetch_object($rs1)) {
            ?>
                <div class="pd_ctg_block">
                    <div class="pd_ctg_heading"> <?php print($row1->cat_title); ?> </div>
                    <div class="pd_ctg_row">
                        <?php
                        //$Query2 = "SELECT cm.cat_id, cm.supplier_id, c.group_id, c.cat_title_de AS cat_title, pg.pg_mime_source FROM category_map AS cm LEFT OUTER JOIN category AS c ON c.group_id = SUBSTRING(cm.cat_id, 1, 3) LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1'  WHERE FIND_IN_SET(".$row1->group_id.", cm.sub_group_ids) GROUP BY c.group_id ORDER BY  RAND() LIMIT 0,4";
                        $Query2 = "SELECT * FROM ( SELECT MIN(cm.cat_id) AS cat_id, MIN(cm.supplier_id) AS supplier_id, c.group_id, MAX(c.cat_title_de) AS cat_title, MAX(c.cat_params_de) AS cat_params, MIN(pg.pg_mime_source_url) AS pg_mime_source, RAND() AS rand_col FROM category_map AS cm LEFT OUTER JOIN category AS c ON c.group_id = SUBSTRING(cm.cat_id, 1, 3) LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE FIND_IN_SET(" . $row1->group_id . ", cm.sub_group_ids) GROUP BY c.group_id) AS subquery ORDER BY rand_col LIMIT 0,4";
                        $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                        if (mysqli_num_rows($rs2) > 0) {
                            while ($row2 = mysqli_fetch_object($rs2)) {
                        ?>
                                <div class="pd_ctg_card">
                                    <a href="artikelarten/<?php print($row2->cat_params); ?>">
                                        <div class="pd_ctg_image"><img loading="lazy" src="<?php print(get_image_link(160, $row2->pg_mime_source)); ?>" alt=""></div>
                                        <div class="pd_ctg_title"> <?php print($row2->cat_title); ?> </div>
                                    </a>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>