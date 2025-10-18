<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="<?php print($GLOBALS['siteURL']); ?>">
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<meta name="keywords" content="<?php print( ((!empty($meta_keywords))? $meta_keywords : config_metakey ) ); ?>">
<meta name="description" content="<?php print( ((!empty($meta_description))? $meta_description : config_metades ) ); ?>">
<?php if(!empty($page_title)){ ?>
    <title><?php print($page_title); ?></title>
<?php } else { ?>
    <title><?php print($GLOBALS['siteName']); ?></title>
<?php } ?>
<link rel="preload" href="<?php print(get_font_link(config_fonts));?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="<?php print(get_font_link(config_fonts));?>"></noscript>
<link rel="preload" href="css/styles.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="css/styles.min.css"></noscript>
<?php include("includes/btn_color.php"); ?>
<script src="js/jquery-2.2.0.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js" defer></script> -->