<?php
include 'lib/openCon.php';
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
$date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
?>
<sitemapindex 
  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  
  <sitemap>
    <loc><?php print($GLOBALS['siteURL']."sitemap-category.xml") ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
  </sitemap>
  <sitemap>
    <loc><?php print($GLOBALS['siteURL']."sitemap-product1.xml") ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
  </sitemap>
  <sitemap>
    <loc><?php print($GLOBALS['siteURL']."sitemap-product2.xml") ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
  </sitemap>
  <sitemap>
    <loc><?php print($GLOBALS['siteURL']."sitemap-product3.xml") ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
  </sitemap>
  <sitemap>
    <loc><?php print($GLOBALS['siteURL']."sitemap-product4.xml") ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
  </sitemap>
  <sitemap>
    <loc><?php print($GLOBALS['siteURL']."sitemap-product5.xml") ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
  </sitemap>
  <sitemap>
    <loc><?php print($GLOBALS['siteURL']."sitemap-product6.xml") ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
  </sitemap>
  
</sitemapindex>
