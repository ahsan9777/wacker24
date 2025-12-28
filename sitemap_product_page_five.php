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
    $rsp = mysqli_query($GLOBALS['conn'], "SELECT pro_cdate, pro_udate, supplier_id, pro_ean, pro_udx_seo_epag_title_params_de FROM products WHERE pro_status='1' AND (pro_id >= 32000 AND pro_id < 40000) ");
    if (mysqli_num_rows($rsp) > 0) {
        while ($rwp = mysqli_fetch_object($rsp)) {
            $dt = $rwp->pro_udate;
            if (empty($rwp->pro_udate)) {
                $dt = $rwp->pro_cdate;
            }
            $date = new DateTime($dt, new DateTimeZone('Europe/Berlin'));
            //$date->format('c');
            $pgURL = $GLOBALS['siteURL'] . $rwp->pro_udx_seo_epag_title_params_de."-".$rwp->pro_ean;
            echo "<url>
                    <loc>" . $pgURL . "</loc>
                    <lastmod>" . $date->format('c') . "</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.1</priority>
                </url>";
        }
    }
    ?>
</urlset>