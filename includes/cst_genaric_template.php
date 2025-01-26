<div class="about_block">
    <div class="about_row">
        <?php if (!empty($cnt_heading)) { ?>
            <div class="about_block_heading"><?php print($cnt_heading); ?></div>
            <?php }
        if (!empty($cnt_image)) {
            if ($cnt_slug == 'impressum') { ?>
                <div class="about_br" style="flex-direction: column;">
                    <div class="about_detail">
                        <?php print($cnt_details); ?>
                    </div>
                    <div class="about_bottom_image" ><img src="<?php print($cnt_image); ?>" alt=""></div>
                <?php } else { ?>
                    <div class="about_br">
                    <div class="about_image"><img src="<?php print($cnt_image); ?>" alt=""></div>
                    <div class="about_detail">
                        <?php print($cnt_details); ?>
                    </div>
                <?php } ?>
                </div>
            <?php } else {
            print($cnt_details);
        } ?>
    </div>
</div>