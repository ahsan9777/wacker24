<?php
$leve_id = 11;
if( (isset($_REQUEST['level_two']) && $_REQUEST['level_two'] > 0) || isset($_REQUEST['level_three']) && $_REQUEST['level_three'] > 0 ){
    if(isset($_REQUEST['level_two'])){

        $leve_id = $_REQUEST['level_two'];

    } elseif(isset($_REQUEST['level_three'])){

        $leve_id = substr($_REQUEST['level_three'],0,3);
    }
} else{
    $leve_id = $_REQUEST['level_one'];
}
?>
<div class="pd_left">
    <div class="categroy_list">
        <h2>Category <div class="categroy_close_mb">X</div>
        </h2>
        <div class="categroy_block">
            <h3> <?php print(returnName("cat_title_de", "category", "group_id", $leve_id)); ?> </h3>
            <ul class="list_checkbox_hide">
                <?php 
                $Query = "SELECT cat_id, group_id, parent_id, cat_title_de AS cat_title, cat_params_de AS cat_params FROM category WHERE parent_id = '".$leve_id."' ORDER BY group_id ASC ";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if(mysqli_num_rows($rs) > 0){
                    while($row = mysqli_fetch_object($rs)){
                ?>
                <li><a href="javascript:void(0)"> <?php print($row->cat_title); ?> </a></li>
                <?php 
                    }
                }
                ?>
            </ul>
        </div>
        <div class="categroy_block">
            <h3>Brands</h3>
            <ul class="list_checkbox_hide category_show_height"><!-- category_show -->
            <?php 
                $Query = "SELECT * FROM `manufacture` WHERE manf_status = '1'";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if(mysqli_num_rows($rs) > 0){
                    while($row = mysqli_fetch_object($rs)){
                ?>
                <li><a href="javascript:void(0)"> <?php print($row->manf_name); ?> </a></li>
                <?php 
                    }
                }
                ?>
            </ul>
            <div class="show-more">(Show More)</div>
        </div>
        <div class="categroy_block">
            <h3>Price</h3>
            <div class="gerenric_range">
                <div class="range-value"> <input type="text" id="amount" readonly></div>
                <div id="slider-range" class="range-bar"></div>
            </div>
        </div>
    </div>
</div>