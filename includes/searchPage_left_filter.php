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
        <h2>Kategorie <div class="categroy_close_mb">X</div>
        </h2>
        <input type="hidden" name="search_keyword" value="<?php print($search_keyword); ?>">
        <div class="categroy_block" id="category_show">
            <div class="loading-container" id="category_loading">
                <div class="loading-text">
                    Laden<span class="dot"></span><span class="dot"></span><span class="dot"></span>
                </div>
            </div>
            
        </div>
        <div class="categroy_block" id="brand_show">
            <div class="loading-container" id="brand_loading">
                <div class="loading-text">
                    Laden<span class="dot"></span><span class="dot"></span><span class="dot"></span>
                </div>
            </div>

        </div>

        <span id="feature_show">
            <div class="loading-container" id="fature_loading">
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
    $(".search_group_id, .search_manf_id").on("click", function() {
        $(".search_pf_fvalue").attr("checked", false)
        $("#frm_left_search").submit();
    });
    $(".search_pf_fvalue").on("click", function() {
        $("#frm_left_search").submit();
    });
    $(window).load(function() {
        setTimeout(function() {
            let Sidefilter_where = "<?php print($Sidefilter_where); ?>";
            let search_group_id_check = <?php echo json_encode($search_group_id_check); ?>;
            $.ajax({
                url: 'ajax_calls.php?action=category_show',
                method: 'POST',
                data: {
                    Sidefilter_where: Sidefilter_where,
                    search_group_id_check: search_group_id_check
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    //console.log(obj);
                    if (obj.status == 1) {
                        $("#category_loading").hide();
                        $("#category_show").html(obj.category_show);
                    }
                }
            }, 5000);
        });
    });
    $(window).load(function() {
        setTimeout(function() {
            let Sidefilter_brandwith = "<?php print($Sidefilter_brandwith); ?>";
            let search_manf_id_check = <?php echo json_encode($search_manf_id_check); ?>;
            $.ajax({
                url: 'ajax_calls.php?action=brand_show',
                method: 'POST',
                data: {
                    Sidefilter_brandwith: Sidefilter_brandwith,
                    search_manf_id_check: search_manf_id_check
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    //console.log(obj);
                    if (obj.status == 1) {
                        $("#brand_loading").hide();
                        $("#brand_show").html(obj.brand_show);
                    }
                }
            }, 6000);
        });
    });
    $(window).load(function() {
        setTimeout(function() {
            let Sidefilter_featurewhere = "<?php print($Sidefilter_featurewhere); ?>";
            let search_pf_fname_check = <?php echo json_encode($search_pf_fname_check); ?>;
            let search_pf_fvalue_check = <?php echo json_encode($search_pf_fvalue_check); ?>;
            $.ajax({
                url: 'ajax_calls.php?action=feature_show',
                method: 'POST',
                data: {
                    Sidefilter_featurewhere: Sidefilter_featurewhere,
                    search_pf_fname_check: search_pf_fname_check,
                    search_pf_fvalue_check: search_pf_fvalue_check
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    //console.log(obj);
                    if (obj.status == 1) {
                        $("#fature_loading").hide();
                        $("#feature_show").html(obj.feature_show);
                    }
                }
            }, 7000);
        });
    });
</script>