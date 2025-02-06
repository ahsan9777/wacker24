<?php
//---------------------- vu_category_map ---------------------------

// CREARE VIEW vu_category_map AS (SELECT cm.cat_id, cm.sub_group_ids, cm.cm_type, cm.supplier_id, pro.pro_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> '');

//---------------------- vu_best_selling_product -------------------

// CREATE VIEW vu_best_selling_product AS (SELECT DISTINCT oi.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id);

//---------------------- vu_products -------------------------------

// ALTER VIEW vu_products AS (SELECT pro.pro_id, pro.supplier_id, pro.pro_manufacture_aid, pro.pro_ean, pro.pro_description_short, cm.sub_group_ids, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1');

//---------------------- vu_wishlist -------------------------------

//CREATE VIEW vu_wishlist AS(SELECT wl.*, cm.cat_id, cm.sub_group_ids, cm.cm_type, pro.pro_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM wishlist AS wl LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = wl.supplier_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = wl.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = wl.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = wl.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1');
?>