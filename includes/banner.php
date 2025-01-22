<section id="banner_section">
    <div class="page_width_1480">
        <div class="banner_inner">
            <div class="banner_slider">
                <?php
                $Query = "SELECT ban_heading_color, ban_detail_color, ban_heading_de AS ban_heading, ban_details_de AS ban_details, ban_file, ban_link, ban_background_color, ban_text_color, ban_button_show FROM banners WHERE ban_status = '1' ORDER BY ban_order ASC";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                        $image_path = $GLOBALS['siteURL'] . "files/banners/" . $row->ban_file;
                        $ext = pathinfo($image_path, PATHINFO_EXTENSION);
                ?>
                        <div>
                            <div class="banner_detail">
                                <h2 style="color: <?php print($row->ban_heading_color); ?>"> <?php print($row->ban_heading); ?> </h2>
                                <p style="color: <?php print($row->ban_detail_color); ?>"> <?php print($row->ban_details); ?> </p>
                                <?php if($row->ban_button_show > 0) { ?>
                                <div class="full_width">
                                    <a href="<?php print( !empty($row->ban_link) ? $GLOBALS['siteURL'].$row->ban_link : 'javascript:void(0)' ); ?> ">
                                        <div class="gerenric_btn" style="background: <?php print($row->ban_background_color); ?>; color: <?php print($row->ban_text_color); ?>; ">Buy Now</div>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <?php if(in_array($ext, array("mp4"))){?>
                            <video autoplay loop>
                                <source src="<?php print($image_path); ?>" type="video/mp4">
                            </video>
                            <?php } else {?>
                            <div class="banner_image"><img src="<?php print($image_path); ?>" alt=""></div>
                            <?php } ?>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>