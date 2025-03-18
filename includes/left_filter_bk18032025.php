<?php
$leve_id = 11;
$left_filter_cat_WhereQuery = "AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.cat_id) )";
if ((isset($level_two_request) && $level_two_request > 0) || (isset($level_three_request) && $level_three_request > 0) || (isset($manf_params_id) && $manf_params_id > 0)) {
    if (isset($level_two_request) && $level_two_request) {

        $leve_id = $level_two_request;
    } elseif (isset($level_three_request) && $level_three_request) {

        $leve_id = substr($level_three_request, 0, 3);
    } elseif (isset($manf_params_id)) {

        $leve_id = 11;
        if(isset($level_one_request) && $level_one_request > 0){
            $leve_id = $level_one_request;
            $left_filter_cat_WhereQuery = "AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids) )";
        }
    }
    $left_filter_cat_title = returnName("cat_title_de", "category", "group_id", $leve_id);
} else {
    //if (!isset($_REQUEST['search_keyword'])) {
        $leve_id = $level_one_request;
        $left_filter_cat_title = returnName("cat_title_de", "category", "group_id", $leve_id);
        if($level_one_request == 20){
            $leve_id = 19;
            $left_filter_cat_title = "Schulranzen";
        }
        //$left_filter_cat_subQuery = "(SELECT COUNT(cm.cat_id) FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids) ) AS count_sub_group_ids";
        $left_filter_cat_WhereQuery = "AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids) )";
    //}
}
$Sidefilter_brandwith = "WITH relevant_suppliers AS (SELECT DISTINCT cm.supplier_id FROM category_map AS cm WHERE FIND_IN_SET(".$leve_id.", cm.sub_group_ids)), filtered_products AS ( SELECT DISTINCT pro.manf_id FROM products AS pro WHERE EXISTS ( SELECT 1 FROM relevant_suppliers rs WHERE rs.supplier_id = pro.supplier_id ) )";
?>
<div class="pd_left" <?php print(isset($_REQUEST['search_keyword'])? 'style="width: 420px;"' : ''); ?> >
    <div class="categroy_list sticky">
        <h2>Kategorie <div class="categroy_close_mb">X</div>
        </h2>
        <div class="categroy_block">
            <!--<h3> <?php print($left_filter_cat_title); ?> </h3>-->
            <ul class="list_checkbox_hide">
                <?php
                //$Query = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title, cat_params_de AS cat_params FROM category WHERE parent_id = '" . $leve_id . "' ORDER BY group_id ASC ";
                //$Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.parent_id = '" . $leve_id . "' ".$left_filter_cat_WhereQuery." ORDER BY cat.group_id ASC ";
                $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, cat_level_two.cat_params_de AS cat_level_params FROM category AS cat LEFT OUTER JOIN category AS cat_level_two ON cat_level_two.group_id = cat.parent_id WHERE cat.parent_id = '" . $leve_id . "' ".$left_filter_cat_WhereQuery." ORDER BY cat.group_id ASC ";
                //print($Query);
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                        if (isset($level_one_request)) {
                            $cat_link = "artikelarten/" . $row->cat_params;
                        } elseif (isset($level_two_request) || isset($level_three_request)) {
                            $cat_link = "artikelarten/".$row->cat_level_params."/". $row->cat_params;
                        }
                ?>
                        <li><a href=" <?php print($cat_link."&".$pro_typeURL); ?> "> <?php print($row->cat_title); ?> </a></li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="categroy_block">
            <h3>Marke</h3>
            <ul class="list_checkbox_hide category_show_height" id="list_checkbox_hide_0">
                <?php
                $brand_link = "";
                //$Query = "SELECT * FROM `manufacture` WHERE manf_status = '1'";
                $Query = " ".$Sidefilter_brandwith." SELECT manf.* FROM manufacture AS manf JOIN filtered_products fp ON manf.manf_id = fp.manf_id WHERE manf.manf_status = '1' ORDER BY manf.manf_id ASC;";
                //print($Query);
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                        if (isset($level_one_request)) {
                            $brand_link = "artikelarten/marke/1/" . $cat_params."/".$row->manf_name_params;
                        } else if (isset($level_two_request)) {
                            $brand_link = "artikelarten/marke/2/" . $cat_params."/".$row->manf_name_params;
                        } else if (isset($level_three_request)){
                            $brand_link = "artikelarten/marke/3/" . $cat_params."/".$row->manf_name_params;
                        }
                ?>
                        <li><a href="<?php print($brand_link); ?>"> <?php print($row->manf_name); ?> </a></li>
                <?php
                    }
                }
                ?>
            </ul>
            <div class="show-more" data-id= "0">(Mehr anzeigen)</div>
        </div>
        <!--<div class="categroy_block">
            <h3>Price</h3>
            <div class="gerenric_range">
                <div class="range-value"> <input type="text" id="amount" readonly></div>
                <div id="slider-range" class="range-bar" style="padding: 0px; font-size: 14px"></div>
            </div>
        </div>-->
    </div>
</div>