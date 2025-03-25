<?php
include("includes/php_includes_top.php");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'feature_category':
            $retValue = array();
            //print_r($_REQUEST);die();
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $level_one_request = $_REQUEST['level_one_request'];
            $pro_typeURL = $_REQUEST['pro_typeURL'];
            $feature_category_data = "";
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
                    $feature_category_data .= '
                    <div class="pd_card">
                        <div class="pd_image"><a href="artikelarten/' . $row1->cat_params . $pro_typeURL . '">
                                <div class="pd_image_inner"><img loading="lazy" src="' . get_image_link(160, $pg_mime_source_href) . '" alt=""></div>
                            </a></div>
                        <div class="pd_detail">
                            <div class="pd_title"><a href="artikelarten/' . $row1->cat_params . $pro_typeURL . '"> ' . $row1->cat_title . ' </a></div>
                            <ul>';
                    //$Query2 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, (SELECT COUNT(cm.cat_id) FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.cat_id) ) AS count_sub_group_ids FROM category AS cat WHERE cat.parent_id = '" . $row1->group_id . "' HAVING count_sub_group_ids > 0 ORDER BY  RAND() LIMIT 0,3";
                    $Query2 = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.parent_id = '" . $row1->group_id . "' AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.cat_id) ) ORDER BY  RAND() LIMIT 0,3";
                    //print($Query2);//die();
                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                    if (mysqli_num_rows($rs2) > 0) {
                        while ($row2 = mysqli_fetch_object($rs2)) {

                            $feature_category_data .= '<li><a href="artikelarten/' . $row1->cat_params . "/" . $row2->cat_params . $pro_typeURL . '"> ' . $row2->cat_title . ' </a></li>';
                        }
                    }

                    $feature_category_data .= '</ul>
                        </div>
                    </div>';
                }
            }
            $retValue = array("status" => "1", "message" => "Record get successfully", "feature_category_data" => $feature_category_data);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
        case 'new_product':
            $retValue = array();
            //print_r($_REQUEST);die();
            $special_price = array();
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $special_price = (!empty($_REQUEST['special_price'])) ? $_REQUEST['special_price'] : [];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];
            $new_product_data = '<h2>Neue Produkte</h2><div class="gerenric_slider_new_product">';
            $Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                        $new_product_data .= '
                        <div>
                            <div class="pd_card">
                                <div class="pd_image"><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"><img loading="lazy" src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"> ' . $row->pro_description_short . ' </a></h5>
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
                                    </div>';
                                    if (!empty($special_price)) {
                                        $new_product_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '> ' . "<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '> ' . "<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>';
                                    } else {
                                        $new_product_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '>' . price_format($row->pbp_price_without_tax) . '€</div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '>' . price_format($row->pbp_price_amount) . '€</div>';
                                    }
        $new_product_data .= '</div>
                            </div>
                        </div>
                    ';

                }
            }
            
            $retValue = array("status" => "1", "message" => "Record get successfully", "new_product_data" => $new_product_data);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'most_sale_product':
            $retValue = array();
            $special_price = array();
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $special_price = (!empty($_REQUEST['special_price'])) ? $_REQUEST['special_price'] : [];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];
            $most_sale_product = "";
        $Query = "SELECT DISTINCT oi.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id WHERE oi.supplier_id IN (SELECT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) AND cm.cm_type = '" . $pro_type . "') AND pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> ''";
        //print($Query);die();
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $most_sale_product .= '<div class="gerenric_white_box">
                <div class="gerenric_product full_column">
                    <h2>Meist verkaufte Produkte</h2>
                    <div class="gerenric_slider_most_sale_product">';
                        while ($row = mysqli_fetch_object($rs)) {
        $most_sale_product .= '<div>
                                <div class="pd_card">
                                    <div class="pd_image"><a href="product/'.$row->supplier_id.'/'.url_clean($row->pro_description_short).'"><img loading="lazy" src="'.get_image_link(160, $row->pg_mime_source_url).'" alt=""></a></div>
                                    <div class="pd_detail">
                                        <h5><a href="product/'.$row->supplier_id.'/'.url_clean($row->pro_description_short).'"> '.$row->pro_description_short.' </a></h5>
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
                                        </div>';
                                            if (!empty($special_price)) {
                    $most_sale_product .= '<div class="pd_prise price_without_tex" '.$price_without_tex_display.'> '."<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>".' </div>
                                            <div class="pd_prise pbp_price_with_tex" '.$pbp_price_with_tex_display.'> '."<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>".' </div>';
                                            } else {
                    $most_sale_product .= '<div class="pd_prise price_without_tex" '.$price_without_tex_display.'>'.price_format($row->pbp_price_without_tax).'€</div>
                                            <div class="pd_prise pbp_price_with_tex" '.$pbp_price_with_tex_display.'>'.price_format($row->pbp_price_amount).'€</div>';
                                        }
                    $most_sale_product .= '</div>
                                </div>
                            </div>';
                            }
$most_sale_product .= '</div>
                </div>
            </div>';
        }
            $retValue = array("status" => "1", "message" => "Record get successfully", "most_sale_product" => $most_sale_product);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

        case 'related_product':
            $retValue = array();
            //print_r($_REQUEST);die();
            $special_price = array();
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $special_price = (!empty($_REQUEST['special_price'])) ? $_REQUEST['special_price'] : [];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];
            $related_product_data = '<h2>Ähnliche Produkte</h2><div class="gerenric_slider_related_product">';
            $Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                        $related_product_data .= '
                        <div>
                            <div class="pd_card">
                                <div class="pd_image"><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"><img loading="lazy" src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"> ' . $row->pro_description_short . ' </a></h5>
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
                                    </div>';
                                    if (!empty($special_price)) {
                                        $related_product_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '> ' . "<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '> ' . "<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>';
                                    } else {
                                        $related_product_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '>' . price_format($row->pbp_price_without_tax) . '€</div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '>' . price_format($row->pbp_price_amount) . '€</div>';
                                    }
        $related_product_data .= '</div>
                            </div>
                        </div>
                    ';

                }
            }
            $retValue = array("status" => "1", "message" => "Record get successfully", "related_product_data" => $related_product_data);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
        
            case 'reference_product':
            $retValue = array();
            //print_r($_REQUEST);die();
            $special_price = array();
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $special_price = (!empty($_REQUEST['special_price'])) ? $_REQUEST['special_price'] : [];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];
            $reference_product_data = '<h2>Referenzen der Produkte</h2><div class="gerenric_slider_reference_product">';
            $Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                        $reference_product_data .= '
                        <div>
                            <div class="pd_card">
                                <div class="pd_image"><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"><img loading="lazy" src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"> ' . $row->pro_description_short . ' </a></h5>
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
                                    </div>';
                                    if (!empty($special_price)) {
                                        $reference_product_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '> ' . "<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '> ' . "<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>';
                                    } else {
                                        $reference_product_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '>' . price_format($row->pbp_price_without_tax) . '€</div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '>' . price_format($row->pbp_price_amount) . '€</div>';
                                    }
        $reference_product_data .= '</div>
                            </div>
                        </div>
                    ';

                }
            }
            $retValue = array("status" => "1", "message" => "Record get successfully", "reference_product_data" => $reference_product_data);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;

            case 'related_category_one':
            $retValue = array();
            //print_r($_REQUEST);die();
            $special_price = array();
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $special_price = (!empty($_REQUEST['special_price'])) ? $_REQUEST['special_price'] : [];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];
            $related_category_one_data = '<h2>Produkte, die mit dieser Kategorie zusammenhängen</h2><div class="gerenric_slider_mostviewed_related_category_one">';
            $Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                        $related_category_one_data .= '
                        <div>
                            <div class="pd_card">
                                <div class="pd_image"><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"><img loading="lazy" src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"> ' . $row->pro_description_short . ' </a></h5>
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
                                    </div>';
                                    if (!empty($special_price)) {
                                        $related_category_one_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '> ' . "<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '> ' . "<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>';
                                    } else {
                                        $related_category_one_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '>' . price_format($row->pbp_price_without_tax) . '€</div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '>' . price_format($row->pbp_price_amount) . '€</div>';
                                    }
        $related_category_one_data .= '</div>
                            </div>
                        </div>
                    ';

                }
            }
            $retValue = array("status" => "1", "message" => "Record get successfully", "related_category_one_data" => $related_category_one_data);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
            
            case 'related_category_two':
            $retValue = array();
            //print_r($_REQUEST);die();
            $special_price = array();
            $pro_type = $_REQUEST['pro_type'];
            $level_one = $_REQUEST['level_one'];
            $special_price = (!empty($_REQUEST['special_price'])) ? $_REQUEST['special_price'] : [];
            $price_without_tex_display = $_REQUEST['price_without_tex_display'];
            $pbp_price_with_tex_display = $_REQUEST['pbp_price_with_tex_display'];
            $related_category_two_data = '<div class="gerenric_slider_mostviewed_related_category_two">';
            $Query = "SELECT * FROM vu_category_map AS cm WHERE cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $level_one . ", cm.sub_group_ids) ORDER BY  RAND() LIMIT 0,12";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                        $related_category_two_data .= '
                        <div>
                            <div class="pd_card">
                                <div class="pd_image"><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"><img loading="lazy" src="' . get_image_link(160, $row->pg_mime_source_url) . '" alt=""></a></div>
                                <div class="pd_detail">
                                    <h5><a href="product/' . $row->supplier_id . '/' . url_clean($row->pro_description_short) . '"> ' . $row->pro_description_short . ' </a></h5>
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
                                    </div>';
                                    if (!empty($special_price)) {
                                        $related_category_two_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '> ' . "<del>" . price_format($row->pbp_price_without_tax) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_without_tax, $special_price['usp_discounted_value'])) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '> ' . "<del>" . price_format($row->pbp_price_amount) . "€</del> <span class='pd_prise_discount'>" . price_format(discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)) . "€ <span class='pd_prise_discount_value'>" . $special_price['usp_discounted_value'] . (($special_price['usp_price_type'] > 0) ? '€' : '%') . "</span> </span>" . ' </div>';
                                    } else {
                                        $related_category_two_data .= '<div class="pd_prise price_without_tex" ' . $price_without_tex_display . '>' . price_format($row->pbp_price_without_tax) . '€</div>
                                        <div class="pd_prise pbp_price_with_tex" ' . $pbp_price_with_tex_display . '>' . price_format($row->pbp_price_amount) . '€</div>';
                                    }
        $related_category_two_data .= '</div>
                            </div>
                        </div>
                    ';

                }
            }
            $retValue = array("status" => "1", "message" => "Record get successfully", "related_category_two_data" => $related_category_two_data);
            $jsonResults = json_encode($retValue);
            print($jsonResults);
            break;
    }
}
