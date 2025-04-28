<meta charset="utf-8">
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<base href="<?php print($GLOBALS['siteURL']); ?>">
<meta name="keywords" content="<?php print( ((!empty($meta_keywords))? $meta_keywords : config_metakey ) ); ?>">
<meta name="description" content="<?php print( ((!empty($meta_description))? $meta_description : config_metades ) ); ?>">
<title><?php print($GLOBALS['siteName']); ?></title>
<link rel="stylesheet" type="text/css" href="<?php print(get_font_link(config_fonts));?>" />
<link href="css/styles.min.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-2.2.0.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js" defer></script> -->
<?php include("includes/btn_color.php"); ?>
