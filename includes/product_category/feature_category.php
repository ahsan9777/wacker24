<?php
$Query1 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params,  (SELECT GROUP_CONCAT(pg.pg_mime_source_url) FROM products_gallery AS pg WHERE  pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' AND pg.supplier_id = (SELECT cm.supplier_id FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids) LIMIT 0,1)) AS pg_mime_source FROM category AS cat  WHERE cat.parent_id = '" . $level_one . "' ORDER BY cat.group_id ASC";
//print($Query1);die();
$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
if (mysqli_num_rows($rs1) > 0) {
    while ($row1 = mysqli_fetch_object($rs1)) {
        if ($level_one_request == 20 && empty($row1->pg_mime_source)) {
            continue;
        }
        $pg_mime_source_href = "files/no_img_1.jpg";
        if (!empty($row1->pg_mime_source)) {
            $pg_mime_source = explode(',', $row1->pg_mime_source);
            //$pg_mime_source_href = "getftpimage.php?img=" . $pg_mime_source[0];
            $pg_mime_source_href = $pg_mime_source[0];
        }
?>
        <div class="pd_card">
            <div class="pd_image"><a href="artikelarten/<?php print($row1->cat_params . $pro_typeURL); ?>">
                    <div class="pd_image_inner"><img loading="lazy" src="<?php print(get_image_link(160, $pg_mime_source_href)); ?>" alt=""></div>
                </a></div>
            <div class="pd_detail">
                <div class="pd_title"><a href="artikelarten/<?php print($row1->cat_params . $pro_typeURL); ?>"> <?php print($row1->cat_title); ?> </a></div>
                <ul>
                    <?php
                    //$Query2 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, (SELECT COUNT(cm.cat_id) FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.cat_id) ) AS count_sub_group_ids FROM category AS cat WHERE cat.parent_id = '" . $row1->group_id . "' HAVING count_sub_group_ids > 0 ORDER BY  RAND() LIMIT 0,3";
                    $Query2 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.parent_id = '" . $row1->group_id . "' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.cat_id) ) ORDER BY  RAND() LIMIT 0,3";
                    //print($Query2);//die();
                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                    if (mysqli_num_rows($rs2) > 0) {
                        while ($row2 = mysqli_fetch_object($rs2)) {
                    ?>
                            <li><a href="artikelarten/<?php print($row1->cat_params . "/" . $row2->cat_params . $pro_typeURL); ?>"> <?php print($row2->cat_title); ?> </a></li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
<?php
    }
}
?>