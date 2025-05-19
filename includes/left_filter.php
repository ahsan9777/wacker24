<?php
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
        $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $leve_id . ", cm.sub_group_ids)";
    }
    $left_filter_cat_WhereQuery = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.cat_id)";
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
    if ($pro_type == 20) {
        $leve_id = 19;
    }
    $left_filter_cat_WhereQuery = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(cat.group_id, cm.sub_group_ids)";
    $Sidefilter_brandwith = " cm.cm_type = '" . $pro_type . "' AND FIND_IN_SET(" . $leve_id . ", cm.sub_group_ids)";
}
//print_r($manf_check);
?>
<div class="pd_left" <?php print(isset($_REQUEST['search_keyword']) ? 'style="width: 420px;"' : ''); ?>>
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
<script>
    $(window).load(function() {
        lf_group_id_inner();
        lf_manf_id_inner();
        lf_pf_fvalue_inner();
    });
    let hasTriggeredClick = false;

    function lf_group_id_inner() {
        //setTimeout(function() {
        let lf_action_type = "<?php print($lf_action_type); ?>";
        let leve_id = "<?php print($leve_id); ?>";
        let left_filter_cat_WhereQuery = "<?php print($left_filter_cat_WhereQuery); ?>";
        let level_check = "<?php print($level_three); ?>";

        $.ajax({
            url: 'ajax_calls.php?action=lf_group_id_inner',
            method: 'POST',
            data: {
                lf_action_type: lf_action_type,
                leve_id: leve_id,
                left_filter_cat_WhereQuery: left_filter_cat_WhereQuery,
                level_check: level_check
            },
            success: function(response) {
                //console.log("response = "+response);
                const obj = JSON.parse(response);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#lf_group_id_loading").hide();
                    $("#lf_group_id_inner").html(obj.lf_group_id_inner);

                    if (level_check > 0 && !hasTriggeredClick) {
                        setTimeout(function() {
                            var lf_group_id = [];
                            $(".lf_group_id:checked").each(function() {
                                lf_group_id.push($(this).val());
                            });
                            lf_manf_id_inner(lf_group_id.join(", "));
                            lf_pf_fvalue_inner(lf_group_id.join(", "));
                            gerenric_product_inner(lf_group_id.join(", "));
                            hasTriggeredClick = true; // Mark as triggered
                        }, 100); // Slight delay to ensure DOM is updated
                    }
                }
            }
            //}, 5000);
        });
    }

    function lf_manf_id_inner(lf_group_id_data) {
        //setTimeout(function() {
        let lf_action_type = "<?php print($lf_action_type); ?>";
        let leve_id = "<?php print($leve_id); ?>";
        let Sidefilter_brandwith = "<?php print($Sidefilter_brandwith); ?>";
        let manf_check = <?php echo json_encode($manf_check); ?>;
        let lf_group_id = "";
        if (typeof lf_group_id_data !== 'undefined' && lf_group_id_data !== null && lf_group_id_data != "") {
            lf_group_id = lf_group_id_data;
        }
        $.ajax({
            url: 'ajax_calls.php?action=lf_manf_id_inner',
            method: 'POST',
            data: {
                lf_action_type: lf_action_type,
                lf_group_id: lf_group_id,
                leve_id: leve_id,
                Sidefilter_brandwith: Sidefilter_brandwith,
                manf_check: manf_check
            },
            success: function(response) {
                //console.log("response = "+response);
                const obj = JSON.parse(response);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#lf_manf_id_inner_loading").hide();
                    $("#lf_manf_id_inner").html(obj.lf_manf_id_inner);
                }
            }
            // }, 5000);
        });
    }

    function lf_pf_fvalue_inner(lf_group_id_data = "", lf_manf_id_data = "") {
        //setTimeout(function() {
        let lf_action_type = "<?php print($lf_action_type); ?>";
        let leve_id = "<?php print($leve_id); ?>";
        let pf_fvalue_check = <?php echo json_encode($pf_fvalue_check); ?>;
        let lf_group_id = "";
        if (typeof lf_group_id_data !== 'undefined' && lf_group_id_data !== null && lf_group_id_data != "") {
            $("#lf_pf_fvalue_inner_loading").show();
            lf_group_id = lf_group_id_data;
        }
        let lf_manf_id = "";
        if (typeof lf_manf_id_data !== 'undefined' && lf_manf_id_data !== null && lf_manf_id_data != "") {
            $("#lf_pf_fvalue_inner_loading").show();
            lf_manf_id = lf_manf_id_data;
        }
        $.ajax({
            url: 'ajax_calls.php?action=lf_pf_fvalue_inner',
            method: 'POST',
            data: {
                lf_group_id: lf_group_id,
                lf_manf_id: lf_manf_id,
                lf_action_type: lf_action_type,
                leve_id: leve_id,
                pf_fvalue_check: pf_fvalue_check
            },
            success: function(response) {
                //console.log("response = "+response);
                const obj = JSON.parse(response);
                //console.log(obj);
                if (obj.status == 1) {
                    $("#lf_pf_fvalue_inner_loading").hide();
                    $("#lf_pf_fvalue_inner").html(obj.lf_pf_fvalue_inner);
                }
                genaric_javascript_file();
                
            }
            //}, 5000);
        });
    }
    function genaric_javascript_file() {
        $(".show-more").click(function() {
            if ($("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + " ").hasClass("category_show_height")) {
                $(this).text("(Weniger anzeigen)");
            } else {
                $(this).text("(Mehr anzeigen)");
            }

            $("#category_show_" + $(this).attr("data-id") + ", #list_checkbox_hide_" + $(this).attr("data-id") + "").toggleClass("category_show_height");
        });
    }
</script>