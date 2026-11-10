<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="<?php print($GLOBALS['siteURL']); ?>">
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<meta name="keywords" content="<?php print( ((!empty($meta_keywords))? $meta_keywords : config_metakey ) ); ?>">
<meta name="description" content="<?php print( ((!empty($meta_description))? $meta_description : config_metades ) ); ?>">
<meta name="publisher" content="Wacker Systems">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<?php
header("X-Robots-Tag: index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1");
?>
<?php if(!empty($page_title)){ ?>
    <title><?php print($page_title); ?></title>
<?php } else { ?>
    <title><?php print($GLOBALS['siteTitle']); ?></title>
<?php } ?>
<link rel="preload" href="css/fonts/sans-serif-fonts/roboto/Roboto-Regular.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="css/fonts/sans-serif-fonts/roboto/Roboto-Bold.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?php print(get_font_link(config_fonts));?>">
<link rel="stylesheet" href="css/styles.min.css?dt=06112026">
<link rel="stylesheet" href="css/responsive.min.css?dt=10112026">
<link rel="stylesheet" href="css/slick-theme.min.css">
<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
<?php include("includes/btn_color.php"); ?>
<script src="js/jquery-3.7.1.min.js"></script>
<script>
if (window.jQuery) {
    var _origJqLoad = jQuery.fn.load;
    jQuery.fn.load = function(fn) {
        if (typeof fn === 'function') {
            if (this[0] === window && document.readyState === 'complete') {
                setTimeout(function() { fn.call(window); }, 0);
                return this;
            }
            return this.on('load', fn);
        }
        return _origJqLoad ? _origJqLoad.apply(this, arguments) : this;
    };
    if (!jQuery.fn.unload) {
        jQuery.fn.unload = function(fn) {
            return typeof fn === 'function' ? this.on('unload', fn) : this;
        };
    }
}
</script>
