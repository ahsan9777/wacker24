<?php
include("../lib/openCon.php");
include("../lib/functions.php");
// Sample product data (you can replace this with data from your database)
//$Query = "SELECT pro.*, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, manf.manf_name, cm.cat_id, cm.sub_group_ids, pg.pg_mime_source_url, pq.pq_id, pq.pq_quantity, pq.pq_upcomming_quantity, pq.pq_status FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN manufacture AS manf ON manf.manf_id = pro.manf_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) LEFT OUTER JOIN products_quantity AS pq ON pq.supplier_id = pro.supplier_id  ORDER BY pro.pro_id ASC";
$Query = "SELECT * FROM vu_products AS pro ORDER BY pro.pro_id ASC";
$rs = mysqli_query($GLOBALS['conn'], $Query);
if (mysqli_num_rows($rs) > 0) {
    while ($row = mysqli_fetch_object($rs)) {

        $product_type = "";
        $special_price = array();
        $sub_group_ids = explode(",", $row->sub_group_ids);

        $special_price = user_special_price("supplier_id", $row->supplier_id);

        if (!$special_price) {
            $special_price = user_special_price("level_two", $sub_group_ids[0]);
        }

        if (!$special_price) {
            $special_price = user_special_price("level_one", $sub_group_ids[1]);
        }

        /*if (!empty($special_price)) {
            $price =  number_format( (discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)), "2", ".", "" );
        }*/ 
        $product_type = returnName("cat_title_de AS cat_title", "category", "group_id", $sub_group_ids[1]); //cat_title_one
        $product_type .= " > ". returnName("cat_title_de AS cat_title", "category", "group_id", $sub_group_ids[0]); //cat_title_two
        $product_type .= " > ". returnName("cat_title_de AS cat_title", "category", "group_id", $row->cat_id); //cat_title_three
        $brand = returnName("manf_name", "manufacture", "manf_id", $row->manf_id); //cat_title_three
        $pg_mime_source_url = $row->pg_mime_source_url;
        if($row->pro_custom_add > 0){
            $pg_mime_source_url = $GLOBALS['siteURL'].$row->pg_mime_source_url;
        }
        $pro_description_long = "";
        $pro_description_long = addslashes(str_replace(array("-"), "", strip_tags($row->pro_description_long)));
	    $pro_description_long = trim(preg_replace('/\s+/', ' ', $pro_description_long));
        $product = array(
                'id' => $row->supplier_id,
                'title' => $row->pro_udx_seo_internetbezeichung,
                'description' => $pro_description_long,
                'link' => $GLOBALS['siteURL'].$row->pro_udx_seo_epag_title_params_de."-".$row->pro_ean,
                'image_link' => $pg_mime_source_url,
                'price' => $row->pbp_price_amount.' EUR',
                'brand' => $brand,
                'condition' => 'new',
                'gtin' => $row->pro_ean,
                'mpn' => $row->pro_manufacture_aid,
                'product_type' => $product_type,
                'availability' => (($row->pq_quantity > 0) ? 'in stock' : 'out of stock'),
                'shipping' => [
                    'country' => 'DE',
                    'service' => 'Standard',
                    'price' => '4.75 EUR',
                ]
                );

                if (!empty($special_price)) {
                    $product['sale_price'] =  number_format( (discounted_price($special_price['usp_price_type'], $row->pbp_price_amount, $special_price['usp_discounted_value'], $row->pbp_tax)), "2", ".", "" ). ' EUR';
                }
                $products[] = $product;
    }
}
$doc = new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput = true;

// Root <rss> with namespace
$rss = $doc->createElement('rss');
$rss->setAttribute('version', '2.0');
$rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:g', 'http://base.google.com/ns/1.0');
$doc->appendChild($rss);

// <channel>
$channel = $doc->createElement('channel');
$rss->appendChild($channel);

$channel->appendChild($doc->createElement('title', $GLOBALS['siteName']));
$channel->appendChild($doc->createElement('link', $GLOBALS['siteURL']));
$channel->appendChild($doc->createElement('description', config_metades));

// Loop through products
foreach ($products as $product) {
    $item = $doc->createElement('item');

    $item->appendChild($doc->createElement('g:id', $product['id']));
    
    // Use createTextNode for content that might contain special characters
    foreach (['title', 'description', 'link', 'image_link', 'price', 'sale_price', 'brand', 'condition', 'gtin', 'mpn', 'store_code', 'product_type', 'availability', 'quantity'] as $tag) {
        if ($tag === 'sale_price' && (empty($product[$tag]) || trim($product[$tag]) === '')) {
            continue;
        }
        $value = isset($product[$tag]) ? (string) $product[$tag] : '';
        $element = $doc->createElement("g:$tag");
        $textNode = $doc->createTextNode($value);
        $element->appendChild($textNode);
        $item->appendChild($element);
    }

    // Shipping
    $shipping = $doc->createElement('g:shipping');
    $shipping->appendChild($doc->createElement('g:country', $product['shipping']['country']));
    $shipping->appendChild($doc->createElement('g:service', $product['shipping']['service']));
    $shipping->appendChild($doc->createElement('g:price', $product['shipping']['price']));
    $item->appendChild($shipping);

    $channel->appendChild($item);
}


// Save the file
$doc->save('google-merchant-feed.xml');
// Trigger download in browser
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="google-merchant-feed.xml"');
readfile('google-merchant-feed.xml');
exit;
