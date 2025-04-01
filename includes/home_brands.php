<?php
    $Query = "SELECT * FROM brands WHERE brand_status = '1'";
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
    ?>
        <div class="hm_section_3">
            <div class="gerenric_white_box">
                <h2 class="txt_align_center">Top Hersteller & Marken</h2>
                <div class="hm_brand_logo">
                    <div class="brand_slider">
                        <?php while ($row = mysqli_fetch_object($rs)) {
                            $brand_image_href = "files/no_img_1.jpg";
                            if (!empty($row->brand_image)) {
                                $brand_image_href = $GLOBALS['siteURL'] . "files/brands/" . $row->brand_image;
                            }
                        ?>
                            <div>
                                <div class="brand_col"><a href="javascript:void(0)">
                                        <div class="brand_item"><img loading="lazy" src="<?php print($brand_image_href) ?>" alt="<?php print($row->brand_name) ?>" title="<?php print($row->brand_name) ?>">
                                        </div>
                                    </a></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="brand_btn"><a href="javascript:void(0)">
                        <div class="gerenric_btn">Alle anzeigen</div>
                    </a></div>
            </div>
        </div>
<?php } ?>