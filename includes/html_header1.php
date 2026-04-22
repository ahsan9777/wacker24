<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="<?php print($GLOBALS['siteURL']); ?>">
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<meta name="keywords" content="<?php print( ((!empty($meta_keywords))? $meta_keywords : config_metakey ) ); ?>">
<meta name="description" content="<?php print( ((!empty($meta_description))? $meta_description : config_metades ) ); ?>">
<title><?php print($GLOBALS['siteName']); ?></title>

<style>
/* Critical Slick Styles (just enough for initial layout) */
.banner_slider { position: relative; display: block; overflow: hidden; }
.banner_slider .slick-slide { display: none; float: left; }
.banner_slider .slick-initialized .slick-slide { display: block; }
.banner_image img { width: 100%; height: auto; display: block; }
.banner_image img {
    width: 100%;
    height: auto;
    aspect-ratio: 1430 / 500; /* adjust to your real ratio */
    object-fit: cover;
}
@font-face {
    font-family: 'Roboto', sans-serif;
    font-display: swap; /* prevents text shifting */
}
</style>

<link rel="preload" href="<?php print(get_font_link(config_fonts));?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="<?php print(get_font_link(config_fonts));?>"></noscript>
<link rel="preload" href="css/styles.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="css/styles.min.css"></noscript>
<?php //include("includes/btn_color.php"); ?>
<!-- <script src="js/jquery-2.2.0.min.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js" defer></script> -->
<link rel="preload" as="image" fetchpriority="high" href="https://www.wacker-buerocenter.de/files/banners/4_banner1.webp" type="image/webp">