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
        display: none;
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
        #banner_section .banner_inner,
        #banner_section .banner_image img {
            height: auto;
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
                        $image_path = $GLOBALS['siteURL'] . "files/banners/" . $row->ban_file;
                        $ext = pathinfo($image_path, PATHINFO_EXTENSION);
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
                            <video autoplay loop>
                                <source src="<?php print($image_path); ?>" type="video/mp4">
                            </video>
                            <?php } else {?>
                            <div class="banner_image"><img <?php print($ban_index > 0 ? 'loading="lazy"' : ''); ?> src="<?php print($image_path); ?>" alt=""></div>
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