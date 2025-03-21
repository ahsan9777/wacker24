<?php
$leve_id = 11;
if ((isset($_REQUEST['level_two']) && $_REQUEST['level_two'] > 0) || (isset($_REQUEST['level_three']) && $_REQUEST['level_three'] > 0) || (isset($_REQUEST['manf_id']) && $_REQUEST['manf_id'] > 0)) {
    if (isset($_REQUEST['level_two'])) {

        $leve_id = $_REQUEST['level_two'];
    } elseif (isset($_REQUEST['level_three'])) {

        $leve_id = substr($_REQUEST['level_three'], 0, 3);
    } elseif (isset($_REQUEST['manf_id'])) {

        $leve_id = 11;
    }
} else {
    if (!isset($_REQUEST['search_keyword'])) {
        $leve_id = $_REQUEST['level_one'];
    }
}
?>
<div class="pd_left" <?php print(isset($_REQUEST['search_keyword']) ? 'style="width: 420px;"' : ''); ?>>
    <form class="categroy_list sticky" name="frm_left_search" id="frm_left_search" method="POST" action="search_result.php" role="form" enctype="multipart/form-data">
        <h2>Category <div class="categroy_close_mb">X</div>
        </h2>
        <input type="hidden" name="search_keyword" value="<?php print($search_keyword); ?>">
        <div class="categroy_block">
            <?php
            $TotalRecCount = "";
            $Query = "SELECT cm.*, COUNT(*) OVER() AS TotalRecCount, COUNT(cat.group_id) AS total_count, cat.group_id, cat.cat_title_de AS cat_title FROM category_map AS cm LEFT OUTER JOIN category AS cat ON FIND_IN_SET(cat.group_id, cm.sub_group_ids) > 1 WHERE cm.supplier_id " . $Sidefilter_where . " GROUP BY cat.group_id ORDER BY cat.group_id ASC ";
            //print($Query);
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            $row = mysqli_fetch_object($rs);
            $TotalRecCount = !empty($row->TotalRecCount) ? $row->TotalRecCount : "";
            //$search_group_id = explode(",", $row->sub_group_ids);
            ?>
            <ul class="category_show <?php print(($TotalRecCount > 5) ? 'category_show_height' : ''); ?>" id="list_checkbox_hide_1">
                <?php
                if (mysqli_num_rows($rs) > 0) {
                    do {
                ?>
                        <li>
                            <label class="gerenric_checkbox">
                                <?php print($row->cat_title . " (" . $row->total_count . ")"); ?>
                                <input type="checkbox" name="search_group_id[]" class="search_group_id" id="search_group_id" value="<?php print($row->group_id); ?>" <?php print(((in_array($row->group_id, $search_group_id_check)) ? 'checked' : '')); ?> >
                                <span class="checkmark"></span>
                            </label>
                        </li>
                <?php
                    } while ($row = mysqli_fetch_object($rs));
                }
                ?>
            </ul>
            <?php if ($TotalRecCount > 5) { ?>
                <div class="show-more" data-id="1">(Show More)</div>
            <?php } ?>
        </div>
        <div class="categroy_block">
            <h3>Brands</h3>
            <?php
            $TotalRecCount = 0;
            //$Query = "SELECT manf.*, COUNT(*) OVER() AS TotalRecCount, (SELECT COUNT(pro.manf_id) FROM products AS pro WHERE pro.manf_id = manf.manf_id AND pro.pro_description_short LIKE '%can%') AS total_count FROM manufacture AS manf WHERE manf.manf_id IN (SELECT pro.manf_id FROM products AS pro WHERE pro.pro_description_short LIKE '%can%') AND manf.manf_status = '1' ORDER BY manf.manf_id ASC";
            //$Query = "SELECT manf.*, COUNT(*) OVER() AS TotalRecCount, " . $Sidefilter_brandSubQuery . " AS total_count FROM manufacture AS manf WHERE manf.manf_id " . $Sidefilter_brandwhere . " AND manf.manf_status = '1' ORDER BY manf.manf_id ASC";
            $Query = " ".$Sidefilter_brandwith." SELECT manf.*, COUNT(*) OVER() AS TotalRecCount, (SELECT COUNT(*) FROM filtered_products WHERE filtered_products.manf_id = manf.manf_id) AS total_count FROM manufacture AS manf WHERE manf.manf_id IN (SELECT manf_id FROM filtered_products) AND manf.manf_status = '1' ORDER BY manf.manf_id ASC;";
            //print($Query);
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            $row = mysqli_fetch_object($rs);
            $TotalRecCount = !empty($row->TotalRecCount) ? $row->TotalRecCount : "";
            ?>
            <ul class="category_show <?php print(($TotalRecCount > 5) ? 'category_show_height' : ''); ?>" id="list_checkbox_hide_2">
                <?php
                if (mysqli_num_rows($rs) > 0) {
                    do {
                ?>
                        <li>
                            <label class="gerenric_checkbox">
                                <?php print($row->manf_name . " (" . $row->total_count . ")"); ?>
                                <input type="checkbox" name="search_manf_id[]" class="search_manf_id" id="search_manf_id" value="<?php print($row->manf_id); ?>" <?php print(((in_array($row->manf_id, $search_manf_id_check)) ? 'checked' : '')); ?> >
                                <span class="checkmark"></span>
                            </label>
                        </li>
                <?php
                    } while ($row = mysqli_fetch_object($rs));
                }
                ?>
            </ul>
            <?php if ($TotalRecCount > 5) { ?>
                <div class="show-more" data-id="2">(Show More)</div>
            <?php } ?>
        </div>
        <?php
        $count = 3;
        $Query1 = "SELECT * FROM products_feature AS pf WHERE pf.pf_fvalue_details = 'FILTER' AND pf.supplier_id " . $Sidefilter_featurewhere . " GROUP BY pf.pf_forder ORDER BY pf.pf_forder ASC";
        //print($Query1);
        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
        if (mysqli_num_rows($rs) > 0) {
            while ($row1 = mysqli_fetch_object($rs1)) {
                $count++;
        ?>
                <div class="categroy_block">
                    <h3><?php print($row1->pf_fname); ?></h3>
                    <?php
                    $TotalRecCount = 0;
                    $Query2 = "SELECT pf.*, COUNT(*) OVER() AS TotalRecCount, COUNT(pf.pf_fvalue) AS total_count FROM products_feature AS pf WHERE pf.pf_forder = '" . $row1->pf_forder . "' AND pf.pf_group_id = '" . $row1->pf_group_id . "' AND pf.supplier_id " . $Sidefilter_featurewhere . " AND pf.pf_fvalue_details = 'FILTER' GROUP BY pf.pf_fvalue ORDER BY pf.pf_forder ASC";
                    //print($Query2);
                    $rs2 = mysqli_query($GLOBALS['conn'], $Query2);
                    $row2 = mysqli_fetch_object($rs2);
                    $TotalRecCount = $row2->TotalRecCount;
                    ?>
                    <ul class="category_show <?php print(($TotalRecCount > 5) ? 'category_show_height' : ''); ?>" id="category_show_<?php print($count); ?>"><!-- category_show -->
                        <?php

                        if (mysqli_num_rows($rs2) > 0) {
                            do { ?>
                                <li>
                                    <label class="gerenric_checkbox">
                                        <?php print($row2->pf_fvalue . " (" . $row2->total_count . ")"); ?>
                                        <input type="radio" name="search_pf_fvalue" id="search_pf_fvalue" class="search_pf_fvalue" value="<?php print($row2->pf_fvalue.";".$row1->pf_forder); ?>" <?php print((($row2->pf_fvalue.";".$row1->pf_forder == $search_pf_fvalue_check) ? 'checked' : '')); ?> >
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                        <?php
                            } while ($row2 = mysqli_fetch_object($rs2));
                        }
                        ?>
                    </ul>
                    <?php if ($TotalRecCount > 5) { ?>
                        <div class="show-more" data-id="<?php print($count); ?>">(Show More)</div>
                    <?php } ?>
                </div>
        <?php
            }
        }
        ?>
        <!--<div class="categroy_block">
            <h3>Price</h3>
            <div class="gerenric_range">
                <div class="range-value"> <input type="text" id="amount" readonly></div>
                <div id="slider-range" class="range-bar" style="padding: 0px; font-size: 14px"></div>
            </div>
        </div>-->
    </form>
</div>

<script>
    $(".search_group_id, .search_manf_id").on("click", function() {
        $(".search_pf_fvalue").attr("checked", false)
        $("#frm_left_search").submit();
    });
    $(".search_pf_fvalue").on("click", function() {
        $("#frm_left_search").submit();
    });
</script>