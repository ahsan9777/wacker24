<?php

/*if($_SERVER['REQUEST_METHOD'] === 'POST'){
$key = "1234567890abcdef1234567890abcdef"; // 32 chars for AES-256
$iv = openssl_random_pseudo_bytes(16);

// Convert form data to query string
$query = http_build_query($_POST);

// Encrypt the data
$encrypted = openssl_encrypt($query, 'AES-256-CBC', $key, 0, $iv);

// Combine IV and encrypted string
$combined = base64_encode($iv . $encrypted);

// Redirect with encrypted query
header("Location: products.php?data=" . urlencode($combined));
}*/
$leve_id = 11;
$manf_check = array();
$pf_fvalue_check = array();
if ((isset($_REQUEST['lf_group_id']) && !empty($_REQUEST['lf_group_id'])) || (isset($level_three) && $level_three > 0) || (isset($level_two) && $level_two > 0)) {
    if (isset($level_three) && $level_three > 0) {
        $leve_id = $level_three;
    } elseif (isset($level_two) && $level_two > 0) {
        $leve_id = $level_two;
    } else {
        $level_three = 0;
        $leve_id = $_REQUEST['lf_group_id'][0];
    }
    if (strlen($leve_id) > 3) {
        $leve_id = substr($leve_id, 0, 3);
        $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $leve_id . ", cm.sub_group_ids)";
    } else {
        if ($pro_type == 20) {
            $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND cm.cat_id_level_two = '" . $leve_id . "'";
        } else {
             $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $leve_id . ", cm.sub_group_ids)";
        }
    }
    if ($pro_type == 20) {
        $left_filter_cat_WhereQuery = " cm.cm_type = '" . $pro_type . "' AND cm.cat_id_level_two = cat.group_id";
    } else {
        $left_filter_cat_WhereQuery = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.cat_id)";
    }
} else {

    if (isset($_REQUEST['lf_manf_id']) && !empty($_REQUEST['lf_manf_id'])) {
        $leve_id = $_REQUEST['lf_parent_id'];
        $manf_check = $_REQUEST['lf_manf_id'];
    } elseif (isset($_REQUEST['lf_pf_fvalue']) && !empty($_REQUEST['lf_pf_fvalue'])) {

        $leve_id = $_REQUEST['lf_parent_id'];
        $pf_fvalue_check = $_REQUEST['lf_pf_fvalue'];
    } else {
        $level_three = 0;
        $leve_id = returnName("group_id", "category", "cat_params_de", $_REQUEST['cat_params_one']);
    }
    /*if ($pro_type == 20) {
        $leve_id = 19;
    }*/
    $left_filter_cat_WhereQuery = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids)";
    if ($pro_type == 20) {
        $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND cm.cat_id_level_two = '" . $leve_id . "'";
    } else {
        $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $leve_id . ", cm.sub_group_ids)";
    }
}
//print_r($manf_check);
?>
<div class="pd_left" <?php print(isset($_REQUEST['search_keyword']) ? 'style="width: 420px;"' : ''); ?>>
    <!--<form class="categroy_list sticky" name="frm_left_search_cat" id="frm_left_search_cat" method="POST" action="<?php print($_SERVER['PHP_SELF']); ?>" role="form" enctype="multipart/form-data">-->
    <form class="categroy_list sticky" name="frm_left_search_cat" id="frm_left_search_cat" method="GET" action="products.php" role="form" enctype="multipart/form-data">
        <input type="hidden" name="lf_parent_id" value="<?php print($leve_id); ?>">
        <input type="hidden" name="pro_type" value="<?php print($pro_type); ?>">
        <h2>Kategorie <div class="categroy_close_mb">X</div>
        </h2>
        <div class="categroy_block ">
            <ul class="list_checkbox_hide lf_group_id_inner" id="lf_group_id_inner">
                <div class="loading-container" id="lf_group_id_loading">
                    <div class="loading-text">
                        Laden<span class="dot"></span><span class="dot"></span><span class="dot"></span>
                    </div>
                </div>
            </ul>
        </div>
        <div class="categroy_block lf_manf_id_inner" id="lf_manf_id_inner">
            <div class="loading-container" id="lf_manf_id_inner_loading">
                <div class="loading-text">
                    Laden<span class="dot"></span><span class="dot"></span><span class="dot"></span>
                </div>
            </div>
        </div>
        <span class="lf_pf_fvalue_inner" id="lf_pf_fvalue_inner">
            <div class="loading-container" id="lf_pf_fvalue_inner_loading">
                <div class="loading-text">
                    Laden<span class="dot"></span><span class="dot"></span><span class="dot"></span>
                </div>
            </div>
        </span>

        <!--<div class="categroy_block">
            <h3>Price</h3>
            <div class="gerenric_range">
                <div class="range-value"> <input type="text" id="amount" readonly></div>
                <div id="slider-range" class="range-bar" style="padding: 0px; font-size: 14px"></div>
            </div>
        </div>-->
    </form>
</div>