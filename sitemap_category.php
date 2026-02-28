<?php
include 'lib/openCon.php';
include 'lib/functions.php';
header("Content-type: text/xml");
print('<?xml version="1.0" encoding="UTF-8"?>');
$date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc><?php echo $GLOBALS['siteURL']; ?></loc>
    <lastmod><?php echo $date->format('c') ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.00</priority>
  </url>
  <?php
    $Query = "SELECT cnt_id, cnt_slug, cnt_title_de AS cnt_title FROM contents WHERE cnt_status = '1' AND footer_id IN (SELECT footer_id FROM footer WHERE footer_status = '1' ORDER BY footer_orderby ASC) ORDER BY footer_id,cnt_orderby ASC";
    //print($Query);die();
    $rs = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rs) > 0) {
        while ($row = mysqli_fetch_object($rs)) {
            echo "<url>
                <loc>" . $GLOBALS['siteURL'] . $row->cnt_slug . "</loc>
                <lastmod>" . $date->format('c') . "</lastmod>
                <changefreq>never</changefreq>
                <priority>0.5</priority>
            </url>";
        }
    }

    ?>
    <url>
        <loc><?php echo $GLOBALS['siteURL']; ?>kontakt</loc>
        <lastmod><?php echo $date->format('c'); ?></lastmod>
        <changefreq>never</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?php echo $GLOBALS['siteURL']; ?>verkäufe-angebote</loc>
        <lastmod><?php echo $date->format('c'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.1</priority>
    </url>
    <?php siteMapCat(0); ?>
</urlset>

<?php
function siteMapCat($parent_id)
{
    $date = new DateTime('now', new DateTimeZone('Europe/Berlin'));
    $cat_WhereQuery = "";
    if (strlen($parent_id) == 3) {
        $cat_WhereQuery = "AND EXISTS (SELECT 1 FROM category_map AS cm WHERE FIND_IN_SET(cat.group_id, cm.cat_id) )";
    }
    $rs = mysqli_query($GLOBALS['conn'], "SELECT cat.cat_id, cat.group_id, cat.parent_id, cat.cat_title_de AS cat_title, cat.cat_params_de AS cat_params, cat.cat_status FROM `category` AS cat WHERE parent_id='" . $parent_id . "' " . $cat_WhereQuery . "");
    if (mysqli_num_rows($rs) > 0) {
        while ($rw = mysqli_fetch_object($rs)) {
            if ($rw->parent_id == 0) {
                $pgURL = $GLOBALS['siteURL'] . "kategorie/" . $rw->cat_params;
            } elseif (strlen($rw->parent_id) == 2) {
                $pgURL = $GLOBALS['siteURL'] . "artikelarten/" . $rw->cat_params;
            } elseif (strlen($rw->parent_id) == 3) {
                $cat_params = returnName("cat_params_de AS cat_params", "category", "group_id", $rw->parent_id);
                $pgURL = $GLOBALS['siteURL'] . "artikelarten/" . $cat_params . "/" . $rw->cat_params;
            }
            //$dt = date("Y-m-d");
            echo "<url>
                <loc>" . $pgURL . "</loc>
                <lastmod>" . $date->format('c') . "</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.1</priority>
            </url>";
            siteMapCat($rw->group_id);
        }
    }
}
?>