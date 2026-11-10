<style>
    #banner_section .banner_inner {
        width: 100%;
        height: 500px;
        position: relative;
        overflow: hidden;
    }
    #banner_section .banner_slider > div {
        position: relative;
    }
    #banner_section .banner_slider:not(.slick-initialized) > div:not(:first-child) {
        display: none !important;
    }
    #banner_section .banner_image img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        display: block;
    }
    #banner_section .banner_detail {
        max-width: 450px;
        padding: 30px 0;
        position: absolute;
        left: 180px;
        z-index: 9999;
    }
    @media screen and (max-width: 1024px) and (min-width: 240px) {
        #banner_section .banner_inner {
            height: auto;
            aspect-ratio: 1280 / 492;
        }
        #banner_section .banner_image img {
            height: auto;
            aspect-ratio: 1280 / 492;
        }
        #banner_section .banner_detail {
            left: 60px;
            height: 100%;
            display: flex;
            flex-wrap: wrap;
            align-content: center;
        }
    }
    @media screen and (max-width: 650px) and (min-width: 240px) {
        #banner_section .banner_detail {
            width: 150px;
            max-width: inherit;
        }
    }
</style>
<section id="banner_section">
    <div class="page_width_1480">
        <div class="banner_inner">
            <div class="banner_slider">
                <?php
                $ban_index = 0;
                $Query = "SELECT ban_heading_color, ban_detail_color, ban_heading_de AS ban_heading, ban_details_de AS ban_details, ban_btn_text_de AS ban_btn_text, ban_file, ban_link, ban_background_color, ban_text_color, ban_button_show FROM banners WHERE ban_status = '1' ORDER BY ban_order ASC";
                $rs = mysqli_query($GLOBALS['conn'], $Query);
                if (mysqli_num_rows($rs) > 0) {
                    while ($row = mysqli_fetch_object($rs)) {
                        $rel_banner = "files/banners/" . $row->ban_file;
                        $image_path = $GLOBALS['siteURL'] . $rel_banner;
                        $ext = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
                ?>
                        <div>
                            <div class="banner_detail">
                                <h2 style="color: <?php print($row->ban_heading_color); ?>"> <?php print($row->ban_heading); ?> </h2>
                                <p style="color: <?php print($row->ban_detail_color); ?>"> <?php print($row->ban_details); ?> </p>
                                <?php if($row->ban_button_show > 0) { ?>
                                <div class="full_width">
                                    <a href="<?php print( !empty($row->ban_link) ? $GLOBALS['siteURL'].$row->ban_link : '#' ); ?> ">
                                        <div class="gerenric_btn" style="background: <?php print($row->ban_background_color); ?>; color: <?php print($row->ban_text_color); ?>; "> <?php print($row->ban_btn_text); ?> </div>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <?php if(in_array($ext, array("mp4"))){?>
                            <?php if($ban_index === 0) { ?>
                            <video autoplay loop muted playsinline preload="metadata">
                                <source src="<?php print($image_path); ?>" type="video/mp4">
                            </video>
                            <?php } else { ?>
                            <video loop muted playsinline preload="none" class="lazy_banner_video">
                                <source data-src="<?php print($image_path); ?>" type="video/mp4">
                            </video>
                            <?php } ?>
                            <?php } else {
                                $img_640 = get_optimized_image($rel_banner, 640, 78);
                                $img_800 = get_optimized_image($rel_banner, 800, 78);
                                $img_1280 = get_optimized_image($rel_banner, 1280, 82);
                                $srcset = $img_640 . " 640w, " . $img_800 . " 800w, " . $img_1280 . " 1280w";
                                $sizes = "(max-width: 767px) 100vw, 1280px";
                            ?>
                            <?php if($ban_index === 0) { ?>
                            <div class="banner_image"><img fetchpriority="high" src="<?php print($img_1280); ?>" srcset="<?php print($srcset); ?>" sizes="<?php print($sizes); ?>" width="1280" height="492" alt=""></div>
                            <?php } else { ?>
                            <div class="banner_image"><img class="lazy_banner_img" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php print($img_1280); ?>" data-srcset="<?php print($srcset); ?>" data-sizes="<?php print($sizes); ?>" width="1280" height="492" alt=""></div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                <?php
                        $ban_index++;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
