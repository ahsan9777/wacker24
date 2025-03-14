<?php
$leve_id = 11;
$left_filter_cat_WhereQuery = "AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.cat_id) )";
if ((isset($_REQUEST['level_two']) && $_REQUEST['level_two'] > 0) || (isset($_REQUEST['level_three']) && $_REQUEST['level_three'] > 0) || (isset($_REQUEST['manf_id']) && $_REQUEST['manf_id'] > 0)) {
    if (isset($_REQUEST['level_two'])) {

        $leve_id = $_REQUEST['level_two'];
    } elseif (isset($_REQUEST['level_three'])) {

        $leve_id = substr($_REQUEST['level_three'], 0, 3);
    } elseif (isset($_REQUEST['manf_id'])) {

        $leve_id = 11;
        if(isset($_REQUEST['level_one']) && $_REQUEST['level_one'] > 0){
            $leve_id = $_REQUEST['level_one'];
            $left_filter_cat_WhereQuery = "AND EXISTS (SELECT 1 FROM category_map AS cm WHERE cm.cm_type = '".$pro_type."' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids) )";
        }
    }
    $left_filter_cat_title = returnName("cat_title_de", "category", "group_id", $leve_id);
} else {
    //if (!isset($_REQUEST['search_keyword'])) {
        $leve_id = $_REQUEST['level_one'];
        $left_filter_cat_title = returnName("cat_title_de", "category", "group_id", $leve_id);
        if($_REQUEST['level_one'] == 20){
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
                $Query = "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params FROM category AS cat WHERE cat.parent_id = '" . $leve_id . "' ".$left_filter_cat_WhereQuery." ORDER BY cat.group_id ASC ";
                //print($Query);
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                        if (isset($_REQUEST['level_one'])) {
                            $cat_link = "products.php?level_two=" . $row->group_id;
                        } elseif (isset($_REQUEST['level_two']) || isset($_REQUEST['level_three'])) {
                            $cat_link = "products.php?level_three=" . $row->group_id;
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
                        if (isset($_REQUEST['level_one'])) {
                            $brand_link = "products.php?level_one=" . $leve_id."&manf_id=".$row->manf_id;
                        } else if (isset($_REQUEST['level_two'])) {
                            $brand_link = "products.php?level_two=" . $leve_id."&manf_id=".$row->manf_id;
                        } else if (isset($_REQUEST['level_three'])){
                            $brand_link = "products.php?level_three=" . $_REQUEST['level_three']."&manf_id=".$row->manf_id;
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